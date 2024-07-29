document.getElementById('leadForm').addEventListener('submit', function(event) {
    // Получение значений полей формы
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const city = document.getElementById('city').value;
    const message = document.getElementById('message');

    // Проверка на заполненность полей в форме
    if (!name || !email || !phone || !city) {
        event.preventDefault();  // Отмена стандартного поведения формы
        message.textContent = 'Все поля должны быть заполнены';
        message.style.color = 'red';
        return;
    }

    // Проверка на корректный Email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        event.preventDefault();  // Отмена стандартного поведения формы
        message.textContent = 'Введите корректный email';
        message.style.color = 'red';
        return;
    }

    // Проверка на корректный телефон
    const phonePattern = /^[0-9]{11}$/;
    if (!phonePattern.test(phone)) {
        event.preventDefault();  // Отмена стандартного поведения формы
        message.textContent = 'Введите корректный номер телефона';
        message.style.color = 'red';
        return;
    }

    // Обработка ответа сервера после отправки формы
    event.preventDefault();  // Отмена стандартного поведения формы
    const formData = new FormData(document.getElementById('leadForm'));  // Создание объекта FormData с данными формы
    fetch('save_lead.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Обработка ответа от сервера
        if (data.includes('заблокирована')) {
            message.textContent = data;  // Вывод сообщения о блокировке
            message.style.color = 'red';
        } else {
            message.textContent = data;  // Вывод сообщения об успешной отправке
            message.style.color = 'green';
            document.getElementById('leadForm').reset();  // Сброс формы
        }
    })
    .catch(error => {
        message.textContent = 'Ошибка отправки данных';  // Вывод сообщения об ошибке
        message.style.color = 'red';
    });
});
