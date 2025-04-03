jQuery(window).on("load", function() {
  console.log("Start to process");
  jQuery("#loading").hide();
});


function insertTag(tag) {
  var textarea = document.getElementById('prompt');
  var cursorPos = textarea.selectionStart;
  var textBefore = textarea.value.substring(0, cursorPos);
  var textAfter  = textarea.value.substring(cursorPos, textarea.value.length);

  textarea.value = textBefore + tag + textAfter;
  textarea.focus();
  textarea.selectionStart = cursorPos + tag.length;
  textarea.selectionEnd = cursorPos + tag.length;
}

function insertHTMLTag(tag) {
  var textarea = document.getElementById('content');
  var cursorPos = textarea.selectionStart;
  var textBefore = textarea.value.substring(0, cursorPos);
  var textAfter  = textarea.value.substring(cursorPos, textarea.value.length);

  textarea.value = textBefore + tag + textAfter;
  textarea.focus();
  textarea.selectionStart = cursorPos + tag.length;
  textarea.selectionEnd = cursorPos + tag.length;
}

function refreshWebsiteCategories() {
  console.log('New state! :');
  jQuery("#loading").show();

  var domainNameInput = document.getElementById('domain_name');
  var domainName = domainNameInput.value;

  var adminEmailInput = document.getElementById('autowp_admin_email');
  var adminEmail = adminEmailInput.value;

  var adminDomainInput = document.getElementById('autowp_domain_name');
  var adminDomain = adminDomainInput.value;

  var userDomainName = adminDomain;
  var userEmail = adminEmail;
  var websiteDomainName = domainName;

  console.log('USER DOMAIN NAME: ' + userDomainName);
  console.log('User Email: ' + userEmail);
  console.log('WEBSITE DOMAIN NAME: ' + websiteDomainName);

  console.log('NEW ATTEMPT STATE!');

  jQuery(document).ready(function($) {
    $.ajax({
      url: "https://api.autowp.app/getWebsiteCategories",
      method: "POST",
      data: {
        user_domainname: userDomainName,
        user_email: userEmail,
        website_domainname: websiteDomainName
      },
      success: function(categories) {
        jQuery("#loading").hide();
        var multiselect = $('#website_category_id');
        multiselect.empty();
        $.each(categories, function(index, category) {
          multiselect.append($('<option>', {
            value: category.id,
            text: category.name
          }));
        });
        multiselect.show();
        $('.btn.btn-primary').html('<i class="bi bi-arrow-clockwise"></i> Refresh');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        jQuery("#loading").hide();
        var errorMessage = "Error refreshing website categories.";
        if (jqXHR.status === 0) {
          errorMessage = "Connection error. Please check your internet connection.";
        } else if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
          errorMessage = jqXHR.responseJSON.error;
        }
        alert(errorMessage);
      }
    });
  });
}

function filterPackages(type) {
  // Tüm paketleri gizle
  var packages = document.querySelectorAll('.package');
  packages.forEach(function(pkg) {
    pkg.style.display = 'none';
  });

  // Seçilen türdeki paketleri göster
  var selectedPackages = document.querySelectorAll('.' + type);
  selectedPackages.forEach(function(pkg) {
    pkg.style.display = 'block';
  });

  // Sekme durumlarını güncelle
  var tabs = document.querySelectorAll('.tablinks');
  tabs.forEach(function(tab) {
    tab.classList.remove('active');
  });

  document.querySelector('button[onclick="filterPackages(\'' + type + '\')"]').classList.add('active');
}

// Sayfa yüklendiğinde aylık paketleri göster
document.addEventListener('DOMContentLoaded', function() {
  filterPackages('monthly');
});


document.addEventListener('DOMContentLoaded', function() {
  const elementTypeSelect = document.getElementById('element_type');
  const promptContainer = document.getElementById('prompt-container');
  const contentContainer = document.getElementById('content-container');

  function toggleFields() {
      if (elementTypeSelect.value === 'static-content') {
          promptContainer.style.display = 'none';
          contentContainer.style.display = 'block';
      } else {
          promptContainer.style.display = 'block';
          contentContainer.style.display = 'none';
      }
  }

  // Trigger on change
  elementTypeSelect.addEventListener('change', toggleFields);
  
  // Initial setup based on default selection
  toggleFields();
});


document.addEventListener('DOMContentLoaded', function() {
  const switchCheckbox = document.getElementById('pricingSwitch');
  const monthlyLabel = document.getElementById('monthly-label');
  const annualLabel = document.getElementById('annual-label');
  
  switchCheckbox.addEventListener('change', function() {
      const monthlyPackages = document.querySelectorAll('.package.monthly');
      const annualPackages = document.querySelectorAll('.package.annual');
      
      monthlyPackages.forEach(pkg => {
          pkg.style.display = this.checked ? 'none' : 'block';
      });
      
      annualPackages.forEach(pkg => {
          pkg.style.display = this.checked ? 'block' : 'none';
      });
      
      monthlyLabel.classList.toggle('active', !this.checked);
      annualLabel.classList.toggle('active', this.checked);
  });
});