/**
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

import { Empty } from 'antd';
import React from 'react';

type Props = {
    className?: string;
    description: any;
};

const NoData = (props: Props) => {
    return (
        <Empty
            image={Empty.PRESENTED_IMAGE_SIMPLE}
            description={props.description}
            className={props.className ?? 'mt-30'}
        >
            {' '}
        </Empty>
    );
};

export default NoData;
