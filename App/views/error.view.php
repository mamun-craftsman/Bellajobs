<?php
loadPartials('head');
loadPartials('navbar');
?>
<section>
      <div class="container mx-auto p-4 mt-4">
         <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?=$data['status']."-"?>Error</div>
         <p class="text-center text-2xl mb-4">
		 <?=$data['message']?>
         </p>
      </div>
</section>

<?php
