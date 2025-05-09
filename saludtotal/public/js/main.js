var sidebar = document.getElementById('sidebar');

function sidebarToggle() {
    if (sidebar.style.display === "none") {
        sidebar.style.display = "block";
    } else {
        sidebar.style.display = "none";
    }
}

var profileDropdown = document.getElementById('ProfileDropDown');

function profileToggle() {
    if (profileDropdown.style.display === "none") {
        profileDropdown.style.display = "block";
    } else {
        profileDropdown.style.display = "none";
    }
}


/**
 * ### Modals ###
 */

function toggleModal(action, elem_trigger)
{
    elem_trigger.addEventListener('click', function () {
        if (action == 'add') {
            let modal_id = this.dataset.modal;
            document.getElementById(`${modal_id}`).classList.add('modal-is-open');
        } else {
            // Automaticlly get the opned modal ID
            let modal_id = elem_trigger.closest('.modal-wrapper').getAttribute('id');
            document.getElementById(`${modal_id}`).classList.remove('modal-is-open');
        }
    });
}


// Check if there is modals on the page
if (document.querySelector('.modal-wrapper'))
{
    // Open the modal
    document.querySelectorAll('.modal-trigger').forEach(btn => {
        toggleModal('add', btn);
    });

    // close the modal
    document.querySelectorAll('.close-modal').forEach(btn => {
        toggleModal('remove', btn);
    });
}

//Seleccion de Doctores
function toggleSection(button) {
    let section = button.nextElementSibling;
    let icon = button.querySelector('.toggle-icon');
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        icon.textContent = '-';
    } else {
        section.classList.add('hidden');
        icon.textContent = '+';
    }
}

function selectDoctor(element) {
    let box_doctor = document.getElementById('selected-doctor');

    if (box_doctor.querySelector('p').textContent !== element.textContent) {
        box_doctor.innerHTML = `<img src='https://via.placeholder.com/100' class='mx-auto rounded-full mb-2'>
        <p class='font-semibold'>${element.textContent}</p>`;
    }
}
