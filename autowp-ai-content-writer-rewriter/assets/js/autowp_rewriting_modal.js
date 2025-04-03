document.addEventListener("DOMContentLoaded", function() {
    var generateRewritePromptBtn = document.getElementById('generateRewritePromptBtn');
    var promptTextarea = document.getElementById('promptTextarea');
    var contentprompt = document.getElementById('content_prompt');
    var rewritePromptModal = new bootstrap.Modal(document.getElementById('rewritePromptModal'));
  
    generateRewritePromptBtn.addEventListener('click', function () {
      var selectedLanguage = document.getElementById('languageSelect').options[document.getElementById('languageSelect').selectedIndex].text;
      var selectedSubtitle = document.getElementById('subtitleSelect').options[document.getElementById('subtitleSelect').selectedIndex].text;
      var selectedNarration = document.getElementById('narrationSelect').options[document.getElementById('narrationSelect').selectedIndex].text;
    
      var generatedPrompt = `[autowp-rewriting-promptcode]${selectedLanguage},${selectedSubtitle},${selectedNarration}[/autowp-rewriting-promptcode]`;
    
      promptTextarea.value = generatedPrompt;
      promptTextarea.select();
      document.execCommand('copy');
      contentprompt.value = generatedPrompt;
    
      rewritePromptModal.hide();
    });
  });
  