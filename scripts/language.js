document.addEventListener('DOMContentLoaded', function() {
    // Check if language is already set in the session
    
    console.log(selectedLanguage)
    // Set the selected language in the dropdown
    var languageDropdown = document.querySelector('.language-dropdown select');
    languageDropdown.value = selectedLanguage;

    
});
