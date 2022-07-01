var x = new MutationObserver(function(e) {
    var txtArea = document.getElementById('htmlContent');
    twemoji.parse(txtArea, {
        size: '16x16'
    });
    var removedElement = e[0].removedNodes[0];
    if (removedElement) {
        var className_removedElement = removedElement.classList?.value;
        if(className_removedElement){
            if (className_removedElement == 'range') {
                var previous_element = e[0].previousSibling.previousSibling;
                if (previous_element) {
                    previous_element.remove()
                }
    
            } else if (className_removedElement == 'img_add') {
                var next_element = e[0].nextSibling.nextSibling;
                if (next_element) {
                    next_element.remove()
                } 
            }
        }
        
    }
});

x.observe(document.getElementById('htmlContent'), {
    childList: true
});