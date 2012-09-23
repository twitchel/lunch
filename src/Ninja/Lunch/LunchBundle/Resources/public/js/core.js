;;;
var NinjaLunch = {};

(function($) {
    function parsePayload(data) {
        console.log(data);
        payload = ((new Function('return "' + data.payload + '";'))());
        if (data.messages && data.messages.length) {
            for (type in data.messages) {
                for (msg in data.messages[type]) {
                    $(window).trigger('lunch.notification', type, data.messages[type][msg]);
                }
            }
        }
        return payload;
    }

    function parse(data) {
        var jsonData;
        // Make sure leading/trailing whitespace is removed (IE can't handle it)
        data = $.trim(data);

        // Attempt to parse using the native JSON parser first
        if (window.JSON && window.JSON.parse) {
            jsonData = window.JSON.parse(data);
            return typeof(jsonData) === 'object' ? parsePayload(jsonData) : data;
        }

        // Make sure the incoming data is actual JSON
        // Logic borrowed from http://json.org/json2.js
        if (rvalidchars.test(data.replace(rvalidescape, "@").replace(rvalidtokens, "]").replace(rvalidbraces, ""))) {

            return parsePayload((new Function("return " + data))());

        }
        $.error("Invalid JSON: " + data);
    }

    $.ajaxPrefilter(function(options) {
        options.dataFilter = function(data, type) {
            if (typeof data !== "string" || !data) {
                return null;
            }

            try {
                return parse(data);
            } catch (e) {
                return data;
            }
        }
    });
    $(document).ready(function() {
        if ($("[rel=tooltip]").length) {
            $("[rel=tooltip]").tooltip();
        }
    });
}(jQuery));
