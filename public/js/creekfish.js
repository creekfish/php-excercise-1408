/**
 * Simple namespace for this project
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */

var CREEKFISH = CREEKFISH || {};

/**
 * Return object "namespaced" using the provided namespace string.
 * @param nsString A dot-separated namespace string, e.g. CREEKFISH.Validation.Password
 * @returns {CREEKFISH|*|{}}
 */
CREEKFISH.initNameSpacedObj = function (nsString) {
    var parts = nsString.split('.'),
        parent = CREEKFISH,
        pl,
        i;

    if (parts[0] === "CREEKFISH") {
        parts = parts.slice(1);
    }

    pl = parts.length;
    for (i = 0; i < pl; i++) {
        //create a property if it doesnt exist
        if (parent[parts[i]] === undefined) {
            parent[parts[i]] = {};
        }
        parent = parent[parts[i]];
    }

    return parent;
};