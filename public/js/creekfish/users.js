/**
 * User registration form helper.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */

var CREEKFISH = CREEKFISH || {};
var Users = CREEKFISH.initNameSpacedObj('CREEKFISH.Users');

$.extend(Users, {

    submitOk: function (jsonData) {
        var callout = Users.getInfoElement();
        callout.text("Registration accepted for " + jsonData[0].email + ". Thank you.");
        Users.getContainerElement().hide();
        callout.show();
    },

    submitFailed: function (jsonData) {
        Users.showErrors(jsonData.messages);
    },

    /**
     * Return true if the two passwords match.
     * @param password1
     * @param password2
     * @returns {boolean}
     */
    passwordsMatch: function (password1, password2) {
        return password1 === password2;
    },

    /**
     * Show a list of error messages for the form.
     * @param messages
     */
    showErrors: function (messages) {
        if (messages.length > 0) {
            var callout = Users.getErrorElement(),
                container = Users.getContainerElement();
            container.hide();
            callout.html("There are problems with your registration:<ul><li>" + messages.join("</li><li>") + "</li></ul>Please fix these issues and resubmit.");
            callout.show();
            $('html, body').animate({
                scrollTop: (callout.offset().top)
            }, 500);
            container.fadeIn(750);
        }
    },

    /**
     * Hide all error messages for the form.
     */
    hideErrors: function () {
        Users.getErrorElement().hide();
    },

    /**
     * Return error message jQuery element
     * @returns {*|jQuery|HTMLElement}
     */
    getErrorElement: function () {
        //@todo populate these and other element names from config
        return $('#error-callout');
    },

    /**
     * Return info message jQuery element
     * @returns {*|jQuery|HTMLElement}
     */
    getInfoElement: function () {
        return $('#info-callout');
    },

    /**
     * Return form container jQuery element
     * @returns {*|jQuery|HTMLElement}
     */
    getContainerElement: function () {
        return $('#user-registration-container');
    }

});