document.addEventListener('DOMContentLoaded', function () {
    var sortableList = document.getElementById('sortable');
    new Sortable(sortableList, {
      animation: 150,
      ghostClass: 'ui-state-highlight'
    });
  
    document.getElementById('new-item-form').addEventListener('submit', function (e) {
      e.preventDefault();
      var name = document.getElementById('name-input').value;
      var prompt = document.getElementById('prompt-input').value;
      var tokens = document.getElementById('tokens-input').value;
      addNewItem(name, prompt, tokens);
    });
  
    var quickAdds = document.querySelectorAll('.quick-add');
    quickAdds.forEach(function(button) {
      button.addEventListener('click', function() {
        var name = this.getAttribute('data-name');
        var prompt = this.getAttribute('data-prompt');
        var tokens = this.getAttribute('data-tokens');
        addNewItem(name, prompt, tokens);
      });
    });
  
    function addNewItem(name, prompt, tokens) {
      var list = document.getElementById('sortable');
      if (list.children.length >= 5) {
        document.getElementById('error-message').innerText = "You cannot add more than 5 items.";
        return;
      }
      var shortenedPrompt = prompt.length > 30 ? prompt.substring(0, 30) + "..." : prompt;
      var itemHtml = `<li class="list-group-item">
                        ${name} - ${shortenedPrompt} (Max Tokens: ${tokens})
                        <span class="delete-btn" onclick="deleteItem(this)">&times;</span>
                      </li>`;
      list.innerHTML += itemHtml;
      document.getElementById('error-message').innerText = '';
      document.getElementById('new-item-form').reset();
    }
  
    window.deleteItem = function (element) {
      element.parentNode.remove();
    }
  });
  