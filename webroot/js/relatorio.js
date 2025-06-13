(function($) {
        $.fn.dataTableExt.oApi.fnGetColumnData = function(oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty) {
            if (typeof iColumn == "undefined")
                return new Array();

            if (typeof bUnique == "undefined")
                bUnique = true;

            if (typeof bFiltered == "undefined")
                bFiltered = true;

            if (typeof bIgnoreEmpty == "undefined")
                bIgnoreEmpty = true;

            var aiRows;

            if (bFiltered == true)
                aiRows = oSettings.aiDisplay;
            else
                aiRows = oSettings.aiDisplayMaster; // all row numbers

            var asResultData = new Array();
            for (var i = 0, c = aiRows.length; i < c; i++) {
                iRow = aiRows[i];
                var aData = this.fnGetData(iRow);
                var sValue = aData[iColumn];

                if (bIgnoreEmpty == true && sValue.length == 0)
                    continue;

                else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1)
                    continue;

                else
                    asResultData.push(sValue);
            }

            return asResultData;
        }
    }(jQuery));


    function fnCreateSelect(aData) {
        var r = '<select><option value=""></option>', i, iLen = aData.length;
        for (i = 0; i < iLen; i++) {
            r += '<option value="' + aData[i] + '">' + aData[i] + '</option>';
        }
        return r + '</select>';
    }
