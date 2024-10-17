document.addEventListener("DOMContentLoaded", () => {
    
    loadServices();

    
    document.getElementById("serviceForm").addEventListener("submit", function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('api.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            loadServices();  
            this.reset();
        });
    });
});


function loadServices() {
    fetch('api.php')
    .then(response => response.json())
    .then(data => {
        const servicesDiv = document.getElementById('services');
        servicesDiv.innerHTML = '';
        data.forEach(service => {
            servicesDiv.innerHTML += `
                <div class="service">
                    <h3>${service.name} ($${service.price})</h3>
                    <p>${service.description}</p>
                    <button onclick="deleteService(${service.id})">Delete</button>
                </div>
            `;
        });
    });
}


function deleteService(id) {
    fetch(`api.php?id=${id}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadServices();  
    });
}
