<?php

declare(strict_types=1);

/*
 * BBBEasy open source platform - https://riadvice.tn/
 *
 * Copyright (c) 2022-2023 RIADVICE SUARL and by respective authors (see below).
 *
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free Software
 * Foundation; either version 3.0 of the License, or (at your option) any later
 * version.
 *
 * BBBEasy is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with BBBEasy; if not, see <http://www.gnu.org/licenses/>.
 */

namespace Actions\Users;

use Enum\UserRole;
use Fake\UserFaker;
use Test\Scenario;

/**
 * @internal
 *
 * @coversNothing
 */
final class DeleteTest extends Scenario
{
    final protected const DELETE_USER_ROUTE = 'DELETE /users/';
    protected $group                        = 'Action User Delete';

    /**
     * @param mixed $f3
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function testNonExistingUser($f3)
    {
        $test = $this->newTest();

        $f3->mock(self::DELETE_USER_ROUTE . UserRole::NON_EXISTING_ID);
        $test->expect($this->compareTemplateToResponse('not_found_error.json'), 'Delete non existing user with id "' . UserRole::NON_EXISTING_ID . '" show an error');

        return $test->results();
    }

    /**
     * @param mixed $f3
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function testValidUser($f3)
    {
        $test = $this->newTest();

        $user = UserFaker::create(UserRole::LECTURER);
        $f3->mock(self::DELETE_USER_ROUTE . $user->id);
        $test->expect($this->compareArrayToResponse(['result' => 'success', 'user' => $user->getUserInfos()]), 'Delete existing user with id "' . $user->id . '" successfully');

        return $test->results();
    }
}
