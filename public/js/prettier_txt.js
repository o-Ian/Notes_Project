function modified_txtAreaVal(IdContent, element) {
    var value = $("#"+IdContent).text();
    var SelectionHtml = getSelectionHtml();
    var selectedText = getSelectionHtml()[0];
    var firstHalf = value.substring(0, getSelectionHtml()[1]);
    var lastHalf = value.substring(32);
    console.log('Primeira metade: '+firstHalf);
    console.log('Segunda metade: '+lastHalf);
    console.log('Valor do texto: '+value);
    console.log('Index Start Selecionado: '+getSelectionHtml()[1]);
    console.log('Index End Selecionado: '+getSelectionHtml()[2]);

    var selectedTextClass = addTxtStyle(selectedText, element);
    var txtArea_replace = firstHalf.concat(selectedTextClass, lastHalf);
    return txtArea_replace;
}

function addTxtStyle(string, style){
    return "<"+style+">" + string + "</"+style+">";
}


function getSelectionHtml() {
    if (typeof window.getSelection != "undefined") {
        var sel = window.getSelection();
        start_index = sel.anchorOffset;
        end_index = sel.focusOffset;

        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            html = container.innerHTML;
        }
    } else if (typeof document.selection != "undefined") {
        if (document.selection.type == "Text") {
            html = document.selection.createRange().htmlText;
        }
    }
    return [html, start_index, end_index]
}