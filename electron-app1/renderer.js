document.addEventListener('DOMContentLoaded', () => {
    const formContainer = document.getElementById('form-container');
    const listContainer = document.getElementById('list-container');
    const messageDiv = document.getElementById('message');
    const assetList = document.getElementById('assets-list');
    const searchInput = document.getElementById('search-input');

    const showFormButton = document.getElementById('show-form');
    const showListButton = document.getElementById('show-list');

    showForm('add');

    function showForm(formName) {
        formContainer.style.display = formName === 'add' ? 'block' : 'none';
        listContainer.style.display = formName === 'list' ? 'block' : 'none';
        clearMessage();
    }

    showFormButton.addEventListener('click', () => {
        showForm('add');
        clearForm();
    });

    showListButton.addEventListener('click', () => {
        showForm('list');
        loadAssets();
    });

    const addAssetForm = document.getElementById('add-asset-form');
    let currentAssetId = null;
    addAssetForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const assetData = {
            id: currentAssetId, 
            name: document.getElementById('name').value,
            location: document.getElementById('location').value,
            place: document.getElementById('place').value,
            responsible_person: document.getElementById('responsible-person').value
        };

        try {
            const response = await fetch(currentAssetId ? 'http://localhost/update_asset.php' : 'http://localhost/add_asset.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(assetData)
            });

            const result = await response.json();
            showMessage(result.message);

            if (response.ok) {
                addAssetForm.reset();
                loadAssets();
                clearForm();
            } else {
                showMessage(result.message || 'Wystąpił problem przy zapisywaniu.', 'error');
            }
        } catch (error) {
            showMessage('Błąd połączenia z serwerem.', 'error');
        }
    });

    async function loadAssets() {
        assetList.innerHTML = '';
        let assets = [];

        try {
            const response = await fetch('http://localhost/get_assets.php');
            assets = await response.json();

            if (assets && assets.length > 0) {
                assets.forEach(addAssetToList);
            } else {
                assetList.innerHTML = '<li>Brak środków trwałych.</li>';
            }
        } catch (error) {
            showMessage('Błąd ładowania środków trwałych.', 'error');
        }

        window.allAssets = assets;
    }

    function addAssetToList(asset) {
        const li = document.createElement('li');
        li.textContent = `ID: ${asset.id} Nazwa: ${asset.name} Lokalizacja: ${asset.location} Miejsce: ${asset.place} Osoba Odpowiedzialna: ${asset.responsible_person}`;
        
        const deleteButton = document.createElement('img');
        deleteButton.src = 'circle.png';
        deleteButton.alt = 'Usuń';
        deleteButton.classList.add('icon-button');
        deleteButton.onclick = () => deleteAsset(asset.id);

        const updateButton = document.createElement('img');
        updateButton.src = 'refresh.png.png';
        updateButton.alt = 'Aktualizuj';
        updateButton.classList.add('icon-button');
        updateButton.onclick = () => showUpdateForm(asset);

        li.appendChild(deleteButton);
        li.appendChild(updateButton);
        assetList.appendChild(li);
    }

    async function deleteAsset(id) {
        try {
            const response = await fetch('http://localhost/delete_asset.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });

            const result = await response.json();
            if (response.ok) {
                showMessage(result.message);
                loadAssets();
            } else {
                showMessage(result.message || 'Nie udało się usunąć środka trwałego.', 'error');
            }
        } catch (error) {
            showMessage('Brak połączenia z serwerem.', 'error');
        }
    }

    function showUpdateForm(asset) {
        formContainer.style.display = 'block';
        listContainer.style.display = 'none';

        document.getElementById('form-title').textContent = 'Aktualizuj Środek Trwały';
        document.getElementById('submit-button').textContent = 'Aktualizuj';

        document.getElementById('name').value = asset.name;
        document.getElementById('location').value = asset.location;
        document.getElementById('place').value = asset.place;
        document.getElementById('responsible-person').value = asset.responsible_person;

        currentAssetId = asset.id;
   }

    function clearForm() {
        document.getElementById('name').value = '';
        document.getElementById('location').value = '';
        document.getElementById('place').value = '';
        document.getElementById('responsible-person').value = '';
        document.getElementById('form-title').textContent = 'Dodaj Nowy Środek Trwały';
        document.getElementById('submit-button').textContent = 'Dodaj środek trwały';
        currentAssetId = null; 
    }

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        filterAssets(query);
    });

    function filterAssets(query) {
        assetList.innerHTML = '';
        const filteredAssets = window.allAssets.filter(asset =>
            asset.name.toLowerCase().includes(query) ||
            asset.location.toLowerCase().includes(query) ||
            asset.place.toLowerCase().includes(query) ||
            asset.responsible_person.toLowerCase().includes(query) ||
            asset.id.toString().includes(query)
        );

        if (filteredAssets.length > 0) {
            filteredAssets.forEach(addAssetToList);
        } else {
            assetList.innerHTML = '<li>Nie znaleziono środka trwałego o podanej nazwie.</li>';
        }
    }

    function showMessage(message, type = 'info') {
        messageDiv.textContent = message;
        messageDiv.className = type === 'error' ? 'error' : '';
    }

    function clearMessage() {
        messageDiv.textContent = '';
        messageDiv.className = '';
    }
});
