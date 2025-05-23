<?php 
    session_start();
if(!isset($_SESSION['logged_in'])){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Lost and Found - Items</title>

      <script>
        function fetchItems() {
                fetch('get_items.php')
                  .then(res => res.json())
                  .then(data => {
                        console.log('Items fetched:', data); // Debug log
                        const grid = document.getElementById('itemsGrid');
                        grid.innerHTML = ''; // Clear existing items
                        data.forEach(item => {
                            const card = document.createElement('div');
                            card.className = 'item-card';
                card.innerHTML = `
              <img src="${item.image_path}" alt="${item.name}">
                            <div class="item-details">
                                <p class="item-name">${item.name}</p>
                                <div class="hover-details">
                                    <p><strong>Date:</strong> ${item.date_found}</p>
                                    <p><strong>Location:</strong> ${item.location}</p>
                                    <p><strong>Description:</strong> ${item.description}</p>
                                  </div>
                              </div>
                          `;
                            grid.appendChild(card);
                          });
                      })
                    .catch(error => console.error('Error fetching items:', error));
              }

            document.addEventListener('DOMContentLoaded', fetchItems);
          </script>

      <style>
        .items-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
                max-width: 1200px;
                margin: auto auto;
                padding: 20px;
              }

            .item-card {
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
                padding: 15px;
                transition: all 0.3s ease;
                position: relative;
                transform-style: preserve-3d;
                perspective: 1000px;
                z-index: 1;
              }

            .item-card img {
                width: 100%;
                height: 160px;
                object-fit: cover;
                border-bottom: 1px solid #eee;
              }

            .item-name {
                margin: 10px 0;
                font-size: 18px;
                font-weight: bold;
                color: #333;
              }

            .hover-details {
                z-index: 100;
                position: absolute;
                bottom: -100%;
                left: 0;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.95);
                padding: 15px;
                transition: all 0.3s ease;
                box-sizing: border-box;
                text-align: left;
                box-shadow: 0 20px 25px rgba(0, 0, 0, 0.35);
                border-radius: 0px 0px 5px 5px;
                display: none;
              }

            .item-card:hover {
                z-index: 100;
                position: relative;
                transform: translateY(-20px) rotateX(5deg);
                box-shadow: 0 20px 25px rgba(0, 0, 0, 0.45);
                border-radius: 0px 0px 5px 5px;
                scale: 1.3;
              }

            .item-card:hover .hover-details {
                position: absolute;
                bottom: -50%;
                z-index: 100;
                display: block;
                transform: translateY(0) rotateX(-5deg);
              }

            .hover-details p {
                margin: 10px 0;
                color: #333;
              }
          </style>
    </head>
  <body>
      <div class="items-grid" id="itemsGrid"></div>
    </body>
  </html>
