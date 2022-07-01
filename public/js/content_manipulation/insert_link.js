!function(doc, win) {
    var input = doc.getElementById('link')//INPUT RECEIVES THE LINK
        , editable = doc.getElementById('htmlContent')//EDITABLE DIV
      , button = doc.getElementById('dataConfirm-link')//BUTTON FOR ADD LINK
      , fragment = null
      , range = null;
  
      function saveSelection() {  
      if (win.getSelection) {
        sel = win.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
          return sel.getRangeAt(0);
        }
      } else if (doc.selection && doc.selection.createRange) {
        return doc.selection.createRange();
      }
      return null;
    }      
    function saveRangeEvent(event) {
      range = saveSelection();
      if (range && !range.collapsed) {
          fragment = range.cloneContents();
      }
    } 
    
    editable.addEventListener('mouseup', saveRangeEvent);
    editable.addEventListener('keyup', saveRangeEvent);
    button.addEventListener('click', function(event) {
      // insert link
        var link = doc.createElement('a');
      link.href = input.value;
      link.target = '_blank';
      input.value = '';
      range.surroundContents(link);

    });
    input.addEventListener('mousedown', function(event) {
      // create fake selection
      if (fragment) {
        var span = doc.createElement('span');
        span.className = 'selected';
        range.surroundContents(span);
      }
    });
    input.addEventListener('blur', function(event) {
      // remove fake selection
        if (fragment) {
        range.deleteContents();
        range.insertNode(fragment);  
        //restoreSelection();
      }
      fragment = null;
    }, true);
    
  }(document, window)