// assets/js/customer.js - Customer Catalog Logic (Member 1)
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const cakeGrid = document.getElementById('cakeGrid');

    if (!cakeGrid) return; // Only run on catalog page

    // Fetch and display cakes
    const fetchAndDisplayCakes = async () => {
        const search = searchInput.value;
        const category = categoryFilter.value;
        
        let apiEndpointUrl = `/Cake_Verse/api/cakes/get-cakes.php?search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`;

        try {
            const response = await fetch(apiEndpointUrl);
            const data = await response.json();
            
            if (data.success) {
                renderCakes(data.data);
            } else {
                cakeGrid.innerHTML = '<div class="empty-state">Failed to load cakes.</div>';
            }
        } catch (error) {
            console.error('Error fetching cakes:', error);
            cakeGrid.innerHTML = '<div class="empty-state">An error occurred while loading cakes.</div>';
        }
    };

    // Render cakes to the grid
    const renderCakes = (cakes) => {
        if (cakes.length === 0) {
            cakeGrid.innerHTML = '<div class="empty-state">No cakes found matching your criteria.</div>';
            return;
        }

        let html = '';
        cakes.forEach(cake => {
            html += `
                <div class="cake-card">
                    <img src="/Cake_Verse/assets/images/${cake.image}" alt="${cake.name}" class="cake-image" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                    <div class="cake-info">
                        <h3 class="cake-name">${cake.name}</h3>
                        <div class="cake-meta">
                            <span><strong>Flavor:</strong> ${cake.flavor}</span>
                            <span><strong>Size:</strong> ${cake.weight}</span>
                            <span><strong>Occasion:</strong> ${cake.category}</span>
                        </div>
                        <div class="cake-price">${cake.formatted_price}</div>
                        <a href="/Cake_Verse/customer/cake-details.php?id=${cake.id}" class="btn btn-block">View Details</a>
                    </div>
                </div>
            `;
        });
        
        cakeGrid.innerHTML = html;
    };

    // Event listeners
    searchInput.addEventListener('input', debounce(fetchAndDisplayCakes, 300));
    categoryFilter.addEventListener('change', fetchAndDisplayCakes);

    // Initial fetch
    fetchAndDisplayCakes();
});

// Debounce helper to prevent too many API calls while typing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
