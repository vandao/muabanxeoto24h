Array.prototype.list = function() {
    var
        limit = this.length,
        orphans = arguments.length - limit,
        scope = orphans > 0 && typeof(arguments[arguments.length - 1]) != "string" ? arguments[arguments.length - 1] : window;

    while (limit--) scope[arguments[limit]] = this[limit];

    if (scope != window) orphans--;

    if (orphans > 0) {
        orphans += this.length;
        while (orphans-- > this.length) scope[arguments[orphans]] = null;
    }
}


function renameObjectKey(object, oldKey, newKey) {
    var newObject = {};

    $.each(object, function(k, v) {
        if (k == oldKey) {
            newObject[newKey] = v;
        } else {
            newObject[k] = v;
        }
    });

    return newObject;
}

function checkLength(o, min, max) {
    if (o.val().length > max || o.val().length < min) {
        return false;
    } else {
        return true;
    }
}

function checkRegexp(o, regexp) {
    if (!(regexp.test(o.val()))) {
        return false;
    } else {
        return true;
    }
}

/**
 * Format number
 */
function formatNumber(num) {
    num = num.toString().replace(/\./g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' +
        num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num);
}


Array.prototype.min = function(comparer) {
    if (this.length === 0) return null;
    if (this.length === 1) return this[0];

    comparer = (comparer || Math.min);

    var v = this[0];
    for (var i = 1; i < this.length; i++) {
        v = comparer(this[i], v);
    }

    return v;
}

Array.prototype.max = function(comparer) {
    if (this.length === 0) return null;
    if (this.length === 1) return this[0];

    comparer = (comparer || Math.max);

    var v = this[0];
    for (var i = 1; i < this.length; i++) {
        v = comparer(this[i], v);
    }

    return v;
}

Array.prototype.unique = function() {
    var unique = [];
    for (var i = 0; i < this.length; i++) {
        if (unique.indexOf(this[i]) == -1) {
            unique.push(this[i]);
        }
    }
    return unique;
}
Array.prototype.removeValue = function(value) {
    return this.splice(this.indexOf(value), 1);
};

/**
 * Add unique value to string
 */
function addUniqueValueToString(value, string) {
    if (string == '') {
        string = value;
    } else {
        var arrayKeyExist = string.split(',');

        if ($.inArray(value.toString(), arrayKeyExist) == -1) {
            string += ',' + value;
        }
    }

    return string;
}

/**
 * Delete unique value from string
 */
function deleteUniqueValueFromString(deleteValue, string) {
    var arrayKeyExist = string.split(',');

    arrayKeyExist = $.grep(arrayKeyExist, function(value) {
        return value != deleteValue;
    });

    return arrayKeyExist.join(',');
}

/**
 * Cookie
 */
function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}

function getCookie(c_name) {
    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1) {
        c_start = c_value.indexOf(c_name + "=");
    }

    if (c_start == -1) {
        c_value = null;
    } else {
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1) {
            c_end = c_value.length;
        }

        c_value = unescape(c_value.substring(c_start, c_end));
    }

    return c_value;
}

function hasCookie(cookieName) {
    var check = getCookie(cookieName);
    if (check != null && check != "") {
        return true;
    } else {
        return false;
    }
}

function getFirstElementInObject(obj) {
    var first;
    for (var i in obj) {
        if (obj.hasOwnProperty(i) && typeof(i) !== 'function') {
            first = obj[i];
            break;
        }
    }

    return first;
}

function getElementByIndexInObject(obj, index) {
    var i = 0;
    for (var attr in obj) {
        if (index === i) {
            return obj[attr];
        }
        i++;
    }
    return null;
}

function URLParser(url) {

    var u = (url) ? url : window.location.href;

    var path = "",
        query = "",
        hash = "",
        params;
    if (u.indexOf("#") > 0) {
        hash = u.substr(u.indexOf("#") + 1);
        u = u.substr(0, u.indexOf("#"));
    }
    if (u.indexOf("?") > 0) {
        path = u.substr(0, u.indexOf("?"));
        query = u.substr(u.indexOf("?") + 1);
        params = query.split('&');
    } else
        path = u;
    return {
        getHost: function() {
            var hostexp = /\/\/([\w.-]*)/;
            var match = hostexp.exec(path);
            if (match != null && match.length > 1)
                return match[1];
            return "";
        },
        getPath: function() {
            var pathexp = /\/\/[\w.-]*(?:\/([^?]*))/;
            var match = pathexp.exec(path);
            if (match != null && match.length > 1)
                return match[1];
            return "";
        },
        getHash: function() {
            return hash;
        },
        getParams: function() {
            return params
        },
        getQuery: function() {
            return query;
        },
        setHash: function(value) {
            if (query.length > 0)
                query = "?" + query;
            if (value.length > 0)
                query = query + "#" + value;
            return path + query;
        },
        setParam: function(name, value) {
            if (!params) {
                params = new Array();
            }
            params.push(name + '=' + value);
            for (var i = 0; i < params.length; i++) {
                if (query.length > 0)
                    query += "&";
                query += params[i];
            }
            if (query.length > 0)
                query = "?" + query;
            if (hash.length > 0)
                query = query + "#" + hash;
            return path + query;
        },
        getParam: function(name) {
            if (params) {
                for (var i = 0; i < params.length; i++) {
                    var pair = params[i].split('=');
                    if (decodeURIComponent(pair[0]) == name)
                        return decodeURIComponent(pair[1]);
                }
            }
            console.log('Query variable %s not found', name);
        },
        hasParam: function(name) {
            if (params) {
                for (var i = 0; i < params.length; i++) {
                    var pair = params[i].split('=');
                    if (decodeURIComponent(pair[0]) == name)
                        return true;
                }
            }
            console.log('Query variable %s not found', name);
        },
        removeParam: function(name) {
            query = "";
            if (params) {
                var newparams = new Array();
                for (var i = 0; i < params.length; i++) {
                    var pair = params[i].split('=');
                    if (decodeURIComponent(pair[0]) != name)
                        newparams.push(params[i]);
                }
                params = newparams;
                for (var i = 0; i < params.length; i++) {
                    if (query.length > 0)
                        query += "&";
                    query += params[i];
                }
            }
            if (query.length > 0)
                query = "?" + query;
            if (hash.length > 0)
                query = query + "#" + hash;
            return path + query;
        }
    }
}

var totalPages = 0;

function pagination(totalPages) {
    if (!URLParser().hasParam('page')) {
        var currentPage = 1;
    } else {
        var currentPage = URLParser().getParam('page');;
    }
    $('#pagination').pagination({
        pages: totalPages,
        cssStyle: 'light-theme',
        currentPage: currentPage,
        onPageClick: function(pageNumber, event) {
            setGetParameter('page', pageNumber);
        }
    });
}

function updateTextParams(text, params) {
    if (typeof params != 'undefined' && Object.keys(params).length > 0) {
        $.each(params, function(k, v) {
            text = text.replace(k, v);
        });
    }

    return text;
}

function replaceBodyText(find, replace) {
    document.body.innerHTML = document.body.innerHTML.replace(new RegExp(find, 'g'), replace);
}

function getInputValueByName(name, type) {
    return $('input[type=' + type + '][name=' + name + ']').val();
}

function getSelectValueByName(name) {
    return $('select[name=' + name + ']').val();
}

function setInputValueByName(name, type, value) {
    $('input[type=' + type + '][name=' + name + ']').val(value);
}

function setSelectValueByName(name, value) {
    $('select[name=' + name + ']').val(value);
}

function isNumberKey(event) {
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
        return true;
    } else if (key < 48 || key > 57) {
        return false;
    } else return true;
}

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};




function cleanEntities(str) {
    var conversions = new Object();
    conversions['ae'] = 'ä|æ|ǽ';
    conversions['oe'] = 'ö|œ';
    conversions['ue'] = 'ü';
    conversions['Ae'] = 'Ä';
    conversions['Ue'] = 'Ü';
    conversions['Oe'] = 'Ö';
    conversions['A'] = 'À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ';
    conversions['a'] = 'à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ';
    conversions['C'] = 'Ç|Ć|Ĉ|Ċ|Č';
    conversions['c'] = 'ç|ć|ĉ|ċ|č';
    conversions['D'] = 'Ð|Ď|Đ';
    conversions['d'] = 'ð|ď|đ';
    conversions['E'] = 'È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ';
    conversions['e'] = 'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ';
    conversions['G'] = 'Ĝ|Ğ|Ġ|Ģ';
    conversions['g'] = 'ĝ|ğ|ġ|ģ';
    conversions['H'] = 'Ĥ|Ħ';
    conversions['h'] = 'ĥ|ħ';
    conversions['I'] = 'Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|Ị|Ỉ';
    conversions['i'] = 'ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|ị|ỉ';
    conversions['J'] = 'Ĵ';
    conversions['j'] = 'ĵ';
    conversions['K'] = 'Ķ';
    conversions['k'] = 'ķ';
    conversions['L'] = 'Ĺ|Ļ|Ľ|Ŀ|Ł';
    conversions['l'] = 'ĺ|ļ|ľ|ŀ|ł';
    conversions['N'] = 'Ñ|Ń|Ņ|Ň';
    conversions['n'] = 'ñ|ń|ņ|ň|ŉ';
    conversions['O'] = 'Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ';
    conversions['o'] = 'ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ';
    conversions['R'] = 'Ŕ|Ŗ|Ř';
    conversions['r'] = 'ŕ|ŗ|ř';
    conversions['S'] = 'Ś|Ŝ|Ş|Š';
    conversions['s'] = 'ś|ŝ|ş|š|ſ';
    conversions['T'] = 'Ţ|Ť|Ŧ';
    conversions['t'] = 'ţ|ť|ŧ';
    conversions['U'] = 'Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|Ứ|Ụ|Ủ|Ũ|Ừ|Ứ|Ử|Ữ';
    conversions['u'] = 'ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|ứ|ụ|ủ|ừ|ứ|ự|ử|ữ';
    conversions['Y'] = 'Ỳ|Ý|Ỵ|Ỷ|Ỹ';
    conversions['y'] = 'ỳ|ý|ỵ|ỷ|ỹ';
    conversions['W'] = 'Ŵ';
    conversions['w'] = 'ŵ';
    conversions['Z'] = 'Ź|Ż|Ž';
    conversions['z'] = 'ź|ż|ž';
    conversions['AE'] = 'Æ|Ǽ';
    conversions['ss'] = 'ß';
    conversions['IJ'] = 'Ĳ';
    conversions['ij'] = 'ĳ';
    conversions['OE'] = 'Œ';
    conversions['f'] = 'ƒ';
    conversions['-'] = ' ';

    for (var i in conversions) {
        var re = new RegExp(conversions[i], "g");
        str = str.replace(re, i);
    }
    return str;
}