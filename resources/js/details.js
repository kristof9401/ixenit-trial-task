import './bootstrap';

const personId = document.getElementById('person-id');

if (personId) {
    getDetails();
}

document.getElementById('person-details-form').addEventListener('submit', (e) =>{
    e.preventDefault();
    document.getElementById('save-data').disabled = true;
    let formDataJsonString = JSON.stringify(collectData());

    let endpoint = '/api/add-new-person';

    if (personId) {
        endpoint = '/api/edit-person';
    }

    axios.post(endpoint, formDataJsonString)
    .then((response) => {
        if (personId) {
            setFormgroupIds(response.data.person);
            alert ('Successful save');
        } else {
            document.location.href = '/';
        }
        document.getElementById('save-data').disabled = false;
    }, (error) => {
        if (error.response && error.response.data) {
            alert(error.response.data.error);
        } else {
            alert('Something went wrong, please try again!');
        }

        document.getElementById('save-data').disabled = false;
    });
})

function collectData() {
    const personData = {
        "first_name": document.getElementById('first-name').value,
        "last_name": document.getElementById('last-name').value,
        "birthdate": document.getElementById('birthdate').value,
        "addresses": [],
        "contact_data": []
    }

    let addressBlocks = document.getElementsByClassName('address-group');

    for (let i = 0; i < addressBlocks.length; i++) {
        let furtherIndex = i > 0 ? `-${i}` : '';

        let address = {
            "postal_code": addressBlocks[i].querySelector(`#postal-code${furtherIndex}`).value,
            "city": addressBlocks[i].querySelector(`#city${furtherIndex}`).value,
            "street": addressBlocks[i].querySelector(`#street${furtherIndex}`).value,
            "type": addressBlocks[i].querySelector(`#address-type${furtherIndex}`).value,
        }

        if (addressBlocks[i].getAttribute('data-id')) {
            address.id = addressBlocks[i].getAttribute('data-id');
        }

        personData.addresses.push(address);
    }

    let contactDataGroups = document.getElementsByClassName('contact-data-group');

    for (let i = 0; i < contactDataGroups.length; i++) {
        let furtherIndex = i > 0 ? `-${i}` : '';

        let contactData = {
            "type": contactDataGroups[i].querySelector(`#contact-type${furtherIndex}`).value,
            "value": contactDataGroups[i].querySelector(`#contact-value${furtherIndex}`).value
        }

        if (contactDataGroups[i].getAttribute('data-id')) {
            contactData.id = contactDataGroups[i].getAttribute('data-id');
        }

        personData.contact_data.push(contactData);
    }

    if (personId) {
        personData.person_id = personId.value;
    }

    return personData;
}

function getDetails() {
    axios.get(`/api/person-details/${personId.value}`)
    .then((response) => {
        parseDetails(response.data);
    }, (error) => {
        if (error.response && error.response.data && error.response.data.error) {
            alert(error.response.data.error);
        } else {
            alert('Something went wrong, please try again!');
        }
    });
}

function parseDetails(data) {
    document.getElementById('first-name').value = data.first_name;
    document.getElementById('last-name').value = data.last_name;
    document.getElementById('birthdate').value = data.birthdate;

    data.addresses.map((value, index) => {
        let furtherIndex = index > 0 ? `-${index}` : '';

        if (index > 0) {
            cloneGroup(document.querySelectorAll('.address-group')[index-1]);
            document.querySelectorAll('.address-group')[index-1].querySelector('.clone-button').remove();
        }

        document.getElementById(`address-type${furtherIndex}`).closest('.form-group').setAttribute('data-id', value.id);
        document.getElementById(`address-type${furtherIndex}`).value = value.type;
        document.getElementById(`postal-code${furtherIndex}`).value = value.postal_code;
        document.getElementById(`city${furtherIndex}`).value = value.city;
        document.getElementById(`street${furtherIndex}`).value = value.street;
    });

    data.contact_data.map((value, index) => {
        let furtherIndex = index > 0 ? `-${index}` : '';

        if (index > 0) {
            cloneGroup(document.querySelectorAll('.contact-data-group')[index-1])
            document.querySelectorAll('.contact-data-group')[index-1].querySelector('.clone-button').remove();
        }

        document.getElementById(`contact-type${furtherIndex}`).closest('.form-group').setAttribute('data-id', value.id);
        document.getElementById(`contact-type${furtherIndex}`).value = value.type;
        document.getElementById(`contact-value${furtherIndex}`).value = value.value;
    });
    
    document.getElementById('person-details-form').classList.remove('hidden');
    document.getElementById('loading-row').classList.add('hidden');
}

document.querySelectorAll(".clone-button").forEach(elem => elem.addEventListener("click", addGroup))


function addGroup(e) {
    const group = e.target.closest('.form-group');
    cloneGroup(group);
    e.target.remove();    
}

function cloneGroup(group) {
    const newGroup = group.cloneNode(true);
    let groupClass = 'contact-data-group';

    newGroup.querySelector('.clone-button').addEventListener("click", addGroup);

    if (newGroup.querySelector('.clone-button').classList.contains('address-btn')) {
        newGroup.querySelector('.clone-button').remove();
        groupClass = 'address-group';
    }
    
    const index = document.getElementsByClassName(groupClass).length;

    newGroup.querySelectorAll('input').forEach((node) => {
        let lastChar = node.id.charAt(node.id.length -1);

        if (index > 1 && isNumber(lastChar)) {
            node.setAttribute('id', node.id.substr(0, node.id.lastIndexOf('-')));
            node.setAttribute('name', node.name.substr(0, node.id.lastIndexOf('-')));
        }

        node.setAttribute('id', `${node.id}-${index}`);
        node.setAttribute('name', `${node.name}-${index}`);
        node.value = '';
    });

    newGroup.querySelectorAll('select').forEach((node) => {
        let lastChar = node.id.charAt(node.id.length -1);

        if (index > 1 && isNumber(lastChar)) {
            node.setAttribute('id', node.id.substr(0, node.id.lastIndexOf('-')));
            node.setAttribute('name', node.name.substr(0, node.id.lastIndexOf('-')));
        }

        node.setAttribute('id', `${node.id}-${index}`);
        node.setAttribute('name', `${node.name}-${index}`);
        node.value = '';
    });

    newGroup.removeAttribute('data-id');

    group.parentNode.insertBefore(newGroup, group.nextSibling);
}

function setFormgroupIds(person) {
    document.querySelectorAll('.address-group').forEach((node, aindex) => {
        node.setAttribute('data-id', person.addresses[aindex].id);
    });

    document.querySelectorAll('.contact-data-group').forEach((node, cindex) => {
        node.setAttribute('data-id', person.contact_data[cindex].id);
    });
}

function isNumber(num) {
    if (typeof num != "string") return false;
    return !isNaN(num) && !isNaN(parseFloat(num));
}
