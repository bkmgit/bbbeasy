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

import React from 'react';
import { Navigate, useLocation } from 'react-router-dom';
import { Location } from 'history';

import AuthService from '../services/auth.service';

import { UserType } from '../types/UserType';
import { SessionType } from '../types/SessionType';

const PrivateRoute = ({ children }) => {
    const { state }: Location = useLocation();
    const currentUser: UserType = AuthService.getCurrentUser();
    const currentSession: SessionType = AuthService.getCurrentSession();

    if (state) {
        return children;
    } else {
        if (currentUser != null && currentSession != null) {
            return children;
        } else {
            return <Navigate to="/login" />;
        }
    }
};

export default PrivateRoute;
