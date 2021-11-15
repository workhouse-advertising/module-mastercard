/*
 * Copyright (c) 2016-2021 Mastercard
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/*global define*/
define(['jquery'], function ($) {
    return function (loadAdapterCallback) {
        var isRenderedPromise = $.Deferred();
        var isActivatedPromise = $.Deferred();

        $.when(
            isRenderedPromise,
            isActivatedPromise
        ).then(loadAdapterCallback);

        return {
            setIsRendered: function () {
                if (isRenderedPromise.state() !== 'resolved') {
                    isRenderedPromise.resolve();
                }
            },

            setIsActivated: function () {
                if (isActivatedPromise.state() !== 'resolved') {
                    isActivatedPromise.resolve()
                }
            },
        }
    }
});
