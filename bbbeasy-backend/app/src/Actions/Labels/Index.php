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

namespace Actions\Labels;

use Actions\Base as BaseAction;
use Actions\RequirePrivilegeTrait;
use Models\Label;

/**
 *Class Index.
 */
class Index extends BaseAction
{
    use RequirePrivilegeTrait;

    /**
     * @throws \JsonException
     */
    public function show($f3, $params): void
    {
        $label  = new Label();
        $labels = $label->getAllLabels();
        $this->logger->debug('collecting labels', ['labels' => json_encode($labels)]);
        $this->renderJson($labels);
    }
}
