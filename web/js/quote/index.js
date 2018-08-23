$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var url = button.data('whatever'); // Extract info from data-* attributes
    var modal = $(this);
    modal.find('.btnSupprimer').attr('href' , url);
});