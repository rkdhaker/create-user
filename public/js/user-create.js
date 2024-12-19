/*==================================================================
                           user-create.js
====================================================================*/

$('#userCreate').validate();

$(document).on('submit', '#userCreate', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    axios.post(route, formData).then(function(response) {
        if(response.data.status == true) {
            $('#userCreate')[0].reset(); 
            var table = $('.data-table').DataTable();
            table.draw();
            toastr.success('Form submitted successfully!');
        }else {
            document.getElementById('error_message').innerHTML = response.data.errors;
            setTimeout(function(){
                document.getElementById('error_message').innerHTML = '';
            },4000);
            return false;
        }
   }).catch(function(error) {

   });
});
