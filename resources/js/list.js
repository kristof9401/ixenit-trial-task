import './bootstrap';

function getdata() {
    axios.get('/api/get-persons')
    .then((response) => {
        drawList(response.data)
    }, (error) => {
        if (error.response.data) {
            alert(error.response.data.error);
        } else {
            alert('Something went wrong, please try again!');
        }
    });
}

function drawList(data) {
    const tbody = document.getElementById('list-content');
    tbody.innerHTML = '';

    data.map((val) => {
        let row = document.createElement('tr');

        let nameCell = document.createElement('td');
        let bithdateCell = document.createElement('td');
        let emailCell = document.createElement('td');
        let telCell = document.createElement('td');
        let actionCell = document.createElement('td');

        actionCell.setAttribute('data-id', val.id);

        nameCell.innerHTML = `${val.first_name} ${val.last_name}`;
        bithdateCell.innerHTML = val.birthdate;
        emailCell.innerHTML = val.email;
        telCell.innerHTML = val.telephone;

        let editBtn = document.createElement('a');
        let deleteBtn = document.createElement('button');

        editBtn.setAttribute('class', 'btn btn-success edit mr-4');
        editBtn.setAttribute('href', `/edit-person/${val.id}`)
        editBtn.innerText = 'Edit';

        deleteBtn.setAttribute('class', 'btn btn-danger delete');
        deleteBtn.addEventListener('click', deletePerson)
        deleteBtn.innerText = 'Delete';

        actionCell.appendChild(editBtn);
        actionCell.appendChild(deleteBtn);

        row.appendChild(nameCell);
        row.appendChild(bithdateCell);
        row.appendChild(emailCell);
        row.appendChild(telCell);
        row.appendChild(actionCell);

        tbody.appendChild(row);
    })
}

function deletePerson(e)
{
    let result = confirm('Are you sure you want to delete the person?');

    e.target.disabled=true;

    let id = e.target.parentNode.getAttribute('data-id');

    if (result) {
        axios.delete(`/api/delete-person/${id}`)
        .then((response) => {
            e.target.closest('tr').remove();
        }, (error) => {
            if (error.response.data) {
                alert(error.response.data.error);
            } else {
                alert('Something went wrong, please try again!');
            }
            
            e.target.disabled=false;
        });
    }
}

getdata();