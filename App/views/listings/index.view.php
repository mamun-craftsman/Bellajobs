<?php
 loadPartials('head');
 loadPartials('navbar');
 loadPartials('top-banner');
 use Framework\Database;
?>

    <section>

      <div class="container mx-auto p-4 mt-4">
        <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">All Jobs</div>
        <?php if(isset($_SESSION['session_message'])): ?>
          <div class="message bg-green-100 p-3 my-3"><?=$_SESSION['session_message']?></div>
          <?php unset($_SESSION['session_message']); ?>
        <?php endif; ?>
        <?php if(isset($keywords) || isset($location)):?>
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Result(s) :</h2>
        <?php endif;?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

          
          <?php foreach($listings as $job) : ?>
          <div class="rounded-lg shadow-md bg-white">
            <div class="p-4">
              <h2 class="text-xl font-semibold"><?= $job['title'] ?></h2>
              <p class="text-gray-700 text-lg mt-2">
              <?= $job['description'] ?>
              </p>
              <ul class="my-4 bg-gray-100 p-4 rounded">
                <li class="mb-2"><strong>Salary:</strong> <?= formatSalary($job['salary']) ?></li>
                <li class="mb-2">
                  <strong>Location:</strong> <?= $job['state'] ?>
                  <?php if($job['state']==='Bangladesh' || $job['state']==='Dhaka'):?>
                  <span
                    class="text-xs bg-blue-500 text-white rounded-full px-2 py-1 ml-2"
                    >Local</span
                  >
                  <?php endif;?>
                </li>
                   <?php if(!empty($job['tags'])) : ?>
              <li class="mb-2">
                <strong>Tags:</strong> <span><?= $job['tags']?></span>,
              </li>
              <?php endif; ?> 
              </ul>
              <?php if(isset($keywords) || isset($location)):?>
                <a href="<?=$job['id']?>"
              <?php else:?>
                <a href="listings/<?=$job['id']?>"
              <?php endif;?>
                class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
              >
                Details
              </a>
              </a>
            </div>
          </div>

          <?php endforeach; ?>
    
            </div>
          </div>
        </div>
      </section>
	<?php
	loadPartials("bottom-banner");
	loadPartials("footer");
	?>


