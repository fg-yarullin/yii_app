
const btn = document.getElementById('_delete-button');

btn.addEventListener('click', function(event) {
    if (confirm('Учетная запись будет удалена. Продолжить?')) {
        deleteProfile2();
    }
    //
    event.stopImmediatePropagation();
    event.preventDefault();
});

function deleteProfile() {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const csrfParam = $('meta[name="csrf-param"]').attr("content");
    const id = document.getElementById('user-id').value;
    const data = {_csrf: csrfToken, id: 'id', delete: true}
    $.ajax({
        type: 'POST',
        url: 'site/profile',
        dataType: 'json',
        data: data,
        response:'text',
        success: showAlert('0'),
        error: alert('error')
        // error: function (jqXHR, exception) {
        //     if (jqXHR.status === 0) {
        //         showAlert('Not connect. Verify Network.');
        //     } else if (jqXHR.status == 404) {
        //         showAlert('Requested page not found (404).');
        //     } else if (jqXHR.status == 500) {
        //         showAlert('Internal Server Error (500).');
        //     } else if (exception === 'parsererror') {
        //         showAlert('Requested JSON parse failed.');
        //     } else if (exception === 'timeout') {
        //         showAlert('Time out error.');
        //     } else if (exception === 'abort') {
        //         showAlert('Ajax request aborted.');
        //     } else {
        //         showAlert('Uncaught Error. ' + jqXHR.responseText);
        //     }
        // }

    });
}

function showAlert(data){
    const parent_div = document.getElementById('app');
    const status_alert = document.createElement('div');
    if (data.toString() === '0') {
        status_alert.className = 'alert alert-success';
        status_alert.innerHTML = 'Действие выполнено успешно';
    } else {
        status_alert.className = 'alert alert-danger';
        status_alert.innerHTML = `Ошибка: ${data}`;
    }
    status_alert.style.maxWidth = '400px'
    status_alert.style.opacity = '0.92';
    status_alert.tabIndex = 1000;
    status_alert.style.position = 'fixed';
    status_alert.style.left = `calc(50%-${status_alert.style.width / 2}`;
    status_alert.style.top =  '65px';// `calc(50%-${status_alert.style.height / 2}`;
    parent_div.appendChild(status_alert)
    setTimeout(function() {
        status_alert.remove();
    }, 2500);
}


function deleteProfile2() {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const csrfParam = $('meta[name="csrf-param"]').attr("content");
    const id = document.getElementById('user-id').value;
    const url = '/index.php?r=site%2deleteProfile';
    fetch(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken
        },
        method: 'POST',
        // credentials: 'include',
        credentials: "same-origin",
        body: JSON.stringify({id: id, delete: true})
    })
    .then((response) => {console.log(response); })
    .catch(err => console.error(err));
}
