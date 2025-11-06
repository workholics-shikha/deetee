 <!-- Modal -->
 <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5" id="exampleModalLabel">Scan QR Code</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body position-relative text-center mx-auto" style="width:fit-content">
                 <img id="modalImage" src="" class="img-fluid" alt="Preview" style="max-height: 80vh;">
             </div>
         </div>
     </div>
 </div>

 <script>
     document.addEventListener('click', function(e) {
         if (e.target.classList.contains('fa-arrows-rotate')) {
             location.reload();
         }
     });

     // implement search for all listings =========== 1st 
     $(document).ready(function() {

         // Trigger search when typing in the input field
         $('#searchInput').on('keyup', function() {
             let searchValue = $(this).val();

             // Only perform the search if the input has 3 or more characters
             if (searchValue.length >= 2 || searchValue.length === 0) {
                 fetchData(1); // Start search from page 1
             }

         });

         // Handle pagination link clicks
         $(document).on('click', '.pagination a', function(e) {
             e.preventDefault();
             let page = $(this).attr('href').split('page=')[1]; // Extract page number from URL
             fetchData(page);
         });

         $("input[type='search']").click(function() {
             let page = 1;
             $('#searchInput').val('')
             fetchData(page);
         });

         // Fetch machines with AJAX
         function fetchData(page) {
             let searchValue = $('#searchInput').val();
             let searchUrl = $('#searchInput').attr('search-url');
             let id = $('#searchInput').data('id');

             $.ajax({
                 url: searchUrl + '?page=' + page, // Append page to the URL
                 type: 'GET',
                 data: {
                     search: searchValue,
                     id: id
                 },
                 success: function(response) {
                     $('#myTableBody').html(response.html); // Update table rows
                     $('#paginationLinks').html(response.pagination); // Update pagination links
                 }
             });
         }
     });

     // ===== Implementing search for multipla tabs =========== 3rd
     const searchUrl = "{{ route('admin.qr-search') }}"; // Global variable

     $(document).ready(function() {
         // Trigger search when typing in the input field
         $('body').on('keyup', '.searchInputQRTab', function() {
             let searchValue = $(this).val();

             if (searchValue.length >= 3 || searchValue.length === 0) {
                 fetchDataQRTab(1); // Start search from page 1
             }
         });

         // Handle pagination link clicks
         $(document).off('click', '.paginationQ a'); // Remove any previous click handlers
         $(document).on('click', '.paginationQ a', function(e) {
             e.preventDefault();

             // Extract page number from URL
             let page = $(this).attr('href').split('page=')[1];

             console.log("Pagination link clicked for page: ", page);

             // Fetch data for the clicked page
             fetchDataQRTab(page);
         });

         $("input[type='search']").click(function() {
             let page = 1;
             $('.searchInputQRTab').val('')
             fetchDataQRTab(page);
         });

         // Function to fetch data via AJAX
         function fetchDataQRTab(page) {
             const urlParams = new URLSearchParams(window.location.search);
             const activeTab = urlParams.get('tab') || 'Operations';

             let searchValue = $('.searchInputQRTab').val();
             const searchUrl = "{{ route('admin.qr-search') }}"; // Ensure this is resolved correctly

             const tabMapping = {
                 Operator: 'operator',
                 Operations: 'operations',
                 Machine: 'machines',
                 Products: 'products',
                 GenericQr: 'genericQr'
             };

             let val = tabMapping[activeTab] || 'operator';

             $.ajax({
                 url: searchUrl + '?page=' + page,
                 type: 'GET',
                 data: {
                     search: searchValue,
                     activeTab: val,
                 },
                 success: function(response) {
                     $('#' + val + '-table-data').html(response.html);
                     $('#' + val + '-paginate').html(response.pagination);
                 },

             });
         }
     });

     // ===== Implementing search for multipla tabs USERS =========== 2nd

     $(document).ready(function() {

         // Trigger search when typing in the input field
         $('.searchInputUserTab').on('keyup', function() {
             let searchValue = $(this).val();

             // Only perform the search if the input has 3 or more characters
             if (searchValue.length > 3 || searchValue.length === 0) {
                 fetchDataTab(1); // Start search from page 1
             }
         });

         // Handle pagination link clicks
         $(document).off('click', '.paginationU a');
         $(document).on('click', '.paginationU a', function(e) {
             e.preventDefault();
             let page = $(this).attr('href').split('page=')[1]; // Extract page number from URL
             fetchDataTab(page);
         });

         $("input[type='search']").click(function() {
             let page = 1;
             $('.searchInputUserTab').val('')
             fetchDataTab(page);
         });

         // Fetch machines with AJAX
         function fetchDataTab(page) {

             const urlParams = new URLSearchParams(window.location.search);
             const activeTab = urlParams.get("tab") || "Operators";

             let searchValue = $('.searchInputUserTab').val();
             let searchUrl   = $('.searchInputUserTab').attr('search-url');

             val = 0;
             if (activeTab == 'Operators') {
                 val = "operator";
             } else if (activeTab == 'Supervisors') {
                 val = "supervisors";
             } else if (activeTab == 'Planning-Team') {
                 val = "planning";
             } else if (activeTab == 'Unit-Head') {
                 val = "unitHead";
             } else if (activeTab == 'Executive-Management') {
                 val = "executive";
             } else if (activeTab == 'Administrators') {
                 val = "administrators";
             }

             $.ajax({
                 url: searchUrl + '?page=' + page, // Append page to the URL
                 type: 'GET',
                 data: {
                     search: searchValue,
                     activeTab: val
                 },
                 success: function(response) {
                     $('#' + val + '-table-data').html(response.html); // Update table rows
                     $('#' + val + '-paginate').html(response.pagination); // Update pagination links
                 }
             });
         }

     });

     $(document).ready(function() {
         $('body').on('click', '.img-zoom', function() {
             var fullSrc = $(this).find('img').attr(
                 'src'); // ✅ Only get the child image of clicked .row-img
             console.log(fullSrc);
             $('#modalImage').attr('src', fullSrc); // Set in modal
             var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
             myModal.show();
         });
     });
     
</script>