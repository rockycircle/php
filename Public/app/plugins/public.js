var Fn = function() {   //公共类
    var XROOT = {
        http: function() {
            var me = this;
            me.post = function(parm) {
                $.ajax({
                    url: parm.url,
                    dataType: "json",
                    data: parm.data,
                    type: 'post',
                    success: parm.success
                });
            },
               me.get = function(url, data, success) {
                $.ajax({
                    url: url,
                    dataType: "json",
                    data: data,
                    type: 'post',
                    success: success
                });
            }
            return me;
        },
        string: function(){
            var me = this;
            me.random = function() {
                return Math.random();
            },
            me.unique = function() {
                return (Math.random() + '').replace('.', 'D');
            },
            me.delPre = function(str, pre) {
                return 'R:E#PLI#C:E' + str.replace('R:E#PLI#C:E' + pre, '');
            },
            me.delSuf = function(str, pre) {
                return str + 'R:E#PLI#C:E'.replace(pre + 'R:E#PLI#C:E', '');
            },
            me.ucwords = function(str) {
                '' + str.toLocaleLowerCase();
                return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                        function(s) {
                            return s.toLocaleUpperCase();
                        }
                );
            },
            me.format = function(format, json) {
                var json = json || {}
                return format.replace(/\{(\w+)\}/g, function(m, key) {
                    return (json[key] || '') + '';
                });
            },
            me.ucfirst = function(str) {
                return ('' + str).replace(/(^([a-zA-Z\p{M}]))/,
                        function(s) {
                            return s.toLocaleUpperCase();
                        }
                );
            },
            me.lc = function(s) {
                return (s + '').toLocaleLowerCase();
            }, //lowerCase
            me.uc = function(s) {
                return (s + '').toLocaleUpperCase();
            }, //upperCase
            me.parseObjectIndex = function(str)
            {

                var start = arguments[1] || 0;
                var arr = str.split('.');
                var re = /\d+/;

                for (var i = start; i < arr.length; i++) {
                    arr[i] = re.test(arr[i]) ? '[' + arr[i] + ']' : '.' + arr[i];
                }
                ;
                str = arr.join('');
                //return start==0? str.replace(/^\./,''): str;
                return str;
            }
            return me;
        },
        json: function(){
            var me = this;
            me.init=function(p, __root) {
                //p = p||{};
                if (typeof(p) === 'object' && this.typeof(p) === 'object') {
                    for (k in p) {
                        if (k != 'parent' && k != '__root' && typeof(p[k]) === 'object' && this.typeof(p[k]) === 'object') {
                            p[k].parent = p;//后赋值
                            p[k].__root = __root;
                            this.init(p[k], __root);//先递归
                        }
                    }
                }
                return p;
            },
           me.help = function(p) {
                var res = {};
                if (typeof(p) === 'object' && this.typeof(p) === 'object') {
                    for (k in p) {
                        if (k != 'parent' && typeof(p[k]) === 'object' && this.typeof(p[k]) === 'object') {
                            res[k].parent = typeof(res[k].parent);//后赋值
                            this.help(p[k]);//先递归
                        }
                    }
                }
                return res;
            },
            me.typeof = function(p) {
                return $.isArray(p) ? /*toString.apply(p) === '[object Array]'?*/'array' : 'object'
            },
            me.encode=function(p) {
                return JSON.stringify(p);
            },
            me.decode=function(p) {
                p = typeof(p) === 'string' ? p : '';
                try {
                    return JSON.parse(p);
                } catch (e) {
                    return false;
                }
            },
            me.getByKeywordInKey=function(json, keyword, fn) {
                fn = fn || false;
                var k, newKey, r = {};
                for (k in json) {
                    if (k.replace(keyword, '') != k) {
                        newKey = fn ? fn(k) : k;
                        r[newKey] = json[k];
                    }
                }
                return r;
            },
            me.setKey = function(json, fn) {
                var k, newK, r = {};
                for (k in json) {
                    newK = fn(k);
                    r[newK] = json[k];
                }
                return r;
            },
            me.toDs = function(json, fieldSet) {
                var fields = arguments[1] || ['value', 'text'];
                fields = typeof(fields) === 'string' ? fields.split(',') : fields;
                //取值模板
                var vals = arguments[2] || ['key', 'value'];
                vals = typeof(vals) === 'string' ? vals.split(',') : vals;

                var tmp = {}, r = [];
                var i = 0;
                var isArray = (fields[0] === 0 && fields[1] === 1) || (fields[0] === 1 && fields[1] === 0);
                for (k in json) {
                    r[i] = r[i] || (isArray ? [] : {});
                    r[i][fields[0]] = vals[0] === 'key' ? k : json[k];
                    r[i][fields[1]] = vals[1] === 'key' ? k : json[k];
                    i++;
                }
                return r;
            },
            me.intersect = function() {
                var k, check, argsLen = arguments.length;
                //if(typeof())
                var r = [];
                for (k in arguments[0]) {
                    check = true;
                    for (i = 1; $i < argsLen; i++) {
                        if (!check)
                            break;
                        check = check && typeof(arguments[i][k] !== "undefined");
                    }
                    if (check)
                        r[k];
                }
                return r;
            },
            me.intersectByValue = function() {
            },
            me.filterEmpty = function(json) {
                var arr = {};
                for (k in json) {
                    if (json[k] != "") {
                        arr[k] = json[k];
                    }
                }
                return arr;
            },
            me.neaten = function(arr, valIndex, keyIndex) {

                var keyIndex = arguments[2] || false;
                var error = arguments[4] || false;
                var r = [];
                if (typeof(valIndex) === 'string') {
                    var _valIndex = me.string.parseObjectIndex(('.' + valIndex).replace(/\.{2,}/g, '.'))
                    valIndex = function(p) {
                        return eval('p' + _valIndex);
                    }
                }

                if (typeof(keyIndex) === 'string') {
                    var _keyIndex = me.string.parseObjectIndex(('.' + keyIndex).replace(/\.{2,}/g, '.'))
                    keyIndex = function(p) {
                        return eval('p' + _keyIndex);
                    }
                }
                var tmpV, tmpK;
                for (var i = 0; i < arr.length; i++) {

                    if (valIndex)
                        tmpV = valIndex(arr[i]);
                    else
                        tmpV = arr[i];

                    tmpK = i;
                    if (keyIndex)
                        tmpK = keyIndex(arr[i]);

                    r[tmpK] = tmpV;
                }
                ;
                return r;
            }
            return me;
        }
    };
    return XROOT;
};

