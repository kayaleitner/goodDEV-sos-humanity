document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.react-apply-button').forEach(function(button) {

        button.addEventListener('click', function() {
            document.getElementById('react-job-application-form-root').dataset.open = '1';
        })
    })
})