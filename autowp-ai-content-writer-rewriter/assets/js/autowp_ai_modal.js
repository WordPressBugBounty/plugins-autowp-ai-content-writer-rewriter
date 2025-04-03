document.addEventListener("DOMContentLoaded", function() {
    var generatePromptBtn = document.getElementById('generatePromptBtn');
    var promptTextarea = document.getElementById('promptTextarea');
    var contentprompt = document.getElementById('content_prompt');
    var keywordInput = document.getElementById('keywordInput');
    var generationMethodSelect = document.getElementById('generationMethod');
    var countrySelect = document.getElementById('countrySelect');
    var promptModal = new bootstrap.Modal(document.getElementById('promptModal'));
   

  
    generatePromptBtn.addEventListener('click', function () {
      var keyword = keywordInput.value.trim();
      if (keyword === '') {
        alert('Anahtar kelime alanı boş olamaz.');
        return;
      }
    
      var selectedGenerationMethod = generationMethodSelect.options[generationMethodSelect.selectedIndex].value;
      var selectedCountry = countrySelect.options[countrySelect.selectedIndex].value;
      var selectedSubtitle = document.getElementById('subtitleSelect').options[document.getElementById('subtitleSelect').selectedIndex].text;
      var selectedNarration = document.getElementById('narrationSelect').options[document.getElementById('narrationSelect').selectedIndex].text;
      var selectedLanguage = document.getElementById('languageSelect').options[document.getElementById('languageSelect').selectedIndex].text;
    
      var generatedPrompt = `[autowp-promptcode]${keyword},${selectedGenerationMethod},${selectedCountry},${selectedLanguage},${selectedSubtitle},${selectedNarration}[/autowp-promptcode]`;
    
      promptTextarea.value = generatedPrompt;
      promptTextarea.select();
      document.execCommand('copy');
      contentprompt.value = generatedPrompt;
    
      promptModal.hide();
    });
  });  