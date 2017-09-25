$(document).ready(function() {
    var fieldMap  = autoPopulateFields.default_add_days_to_field.fieldMap;
    var relationMap = autoPopulateFields.default_add_days_to_field.relationMap;

    function format_date(date, valType) {
        var date = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();
        if (/_mdy/.test(valType)) {
            return month+'-'+day+'-'+year;
        } else if (/_dmy/.test(valType)) {
                return day+'-'+month+'-'+year;
        } else {
                return year+'-'+month+'-'+day;
        }
    }

    for (var key in relationMap) {   
        $("#"+key+"-tr").change(function () {
            // var p_date = ;
            var field_name = relationMap[key];
            var duration = fieldMap[field_name];
            var dateStr = $("#"+key+"-tr")[0].value;
            
            //todo get the date object from dateStr and also get dateformat.
            var dateVal = new Date();
            dateVal.addDays(duration);
            var val = format_date(dateVal, "");
            $("#"+key+"-tr"+"[name="+field_name+"]")[0].value = val;
        });
    }

});