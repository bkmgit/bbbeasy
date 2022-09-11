<?php

declare(strict_types=1);

/*
 * Hivelvet open source platform - https://riadvice.tn/
 *
 * Copyright (c) 2022 RIADVICE SUARL and by respective authors (see below).
 *
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free Software
 * Foundation; either version 3.0 of the License, or (at your option) any later
 * version.
 *
 * Hivelvet is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with Hivelvet; if not, see <http://www.gnu.org/licenses/>.
 */

namespace Actions\Account;

use Actions\Base as BaseAction;
use Enum\ResponseCode;
use Enum\UserStatus;
use Models\User;
use Respect\Validation\Validator;
use Utils\SecurityUtils;
use Validation\DataChecker;

/**
 * Class Register.
 */
class Register extends BaseAction
{
    public function signup($f3): void
    {
        $form        = $this->getDecodedBody()['data'];
        $dataChecker = new DataChecker();

        $dataChecker->verify($form['username'], Validator::length(4)->setName('username'));
        $dataChecker->verify($form['email'], Validator::email()->setName('email'));
        $dataChecker->verify($form['password'], Validator::length(8)->setName('password'));
        $dataChecker->verify($form['confirmPassword'], Validator::length(8)->equals($form['password'])->setName('confirmPassword'));
        // @fixme: the agreement must be accepted only if there are terms for the website
        // otherwise in the login it should look for available terms of they were not previously available and ask to accept them
        $dataChecker->verify($form['agreement'], Validator::trueVal()->setName('agreement'));

        /** @todo : move to locales */
        $error_message = 'User could not be added';
        $response_code = ResponseCode::HTTP_BAD_REQUEST;
        if ($dataChecker->allValid()) {
            $user = new User();
            $compliant = SecurityUtils::isGdprCompliant($form['password']);
            $common = SecurityUtils::credentialsAreCommon($form['username'], $form['email'], $form['password']);
            $users = $this->getUsersByUsernameOrEmail($form['username'], $form['email']);
            $found = $user->usernameOrEmailExists($form['username'], $form['email'], $users);

            if (!$compliant) {
                $this->logger->error($error_message, ['error' => $compliant]);
                $this->renderJson(['message' => $compliant], $response_code);
            } elseif ($common) {
                $this->logger->error($error_message, ['error' => $common]);
                $this->renderJson(['message' => $common], $response_code);
            } elseif ($found) {
                $this->logger->error($error_message, ['error' => $found]);
                $this->renderJson(['message' => $found], ResponseCode::HTTP_PRECONDITION_FAILED);
            } else {
                $user->email    = $form['email'];
                $user->username = $form['username'];
                $user->password = $form['password'];
                $user->role_id  = 2;
                $user->status   = UserStatus::PENDING;

                try {
                    $user->save();
                } catch (\Exception $e) {
                    $this->logger->error($error_message, ['user' => $user->toArray(), 'error' => $e->getMessage()]);
                    $this->renderJson(['message' => $error_message], ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
                    return;
                }
                $this->logger->info('User successfully registered', ['user' => $user->toArray()]);
                $this->renderJson(['result' => 'success', ResponseCode::HTTP_CREATED]);
            }
        } else {
            $this->logger->error($error_message, ['errors' => $dataChecker->getErrors()]);
            $this->renderJson(['errors' => $dataChecker->getErrors()], ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
