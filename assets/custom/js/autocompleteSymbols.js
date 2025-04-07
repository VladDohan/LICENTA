document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const suggestionsContainer = document.getElementById('suggestionsContainer');

    // Show/hide dropdown
    searchInput.addEventListener('focus', () => {
        suggestionsContainer.classList.add('visible');
        filterSuggestions();
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.autocomplete-container')) {
            suggestionsContainer.classList.remove('visible');
        }
    });

    // Filter function
    function filterSuggestions() {
        const query = searchInput.value.toUpperCase();
        const items = suggestionsContainer.getElementsByClassName('suggestion-item');

        Array.from(items).forEach(item => {
            const symbol = item.dataset.symbol.toUpperCase();
            const name = item.dataset.name.toUpperCase();
            const match = symbol.includes(query) || name.includes(query);
            item.style.display = match ? 'block' : 'none';
        });
    }

    // Input events
    searchInput.addEventListener('input', filterSuggestions);

    // Select suggestion
    suggestionsContainer.addEventListener('click', (e) => {
        if (e.target.closest('.suggestion-item')) {
            const symbol = e.target.closest('.suggestion-item').dataset.symbol;
            searchInput.value = symbol;
            suggestionsContainer.classList.remove('visible');
        }
    });
});