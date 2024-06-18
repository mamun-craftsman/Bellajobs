<?php
 loadPartials('head');
 loadPartials('navbar');
 loadPartials('top-banner');
 use Framework\Database;
?>
<section class="flex justify-center items-center mt-20">
      <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Edit and Save the job</h2>
        <form method="POST" action="/listings/<?=$listing['id']?>">
		<input type="hidden" name="_method" value="PUT">
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Job Info
          </h2>
          <?php if(isset($errors)) :?>
              <?php foreach($errors as $error) :?>
                <div class="message bg-red-100 my-3"><?=$error?></div>
              <?php endforeach; ?>
          <?php endif; ?>
          <div class="mb-4">
            <input
              type="text"
              name="title"
              placeholder="Job Title"
              value="<?php echo isset($listing['title']) ? $listing['title'] : ''; ?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <textarea
              name="description"
              placeholder="Job Description"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            ><?php echo isset($listing['description']) ? $listing['description'] : '';?></textarea>
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="salary"
              placeholder="Annual Salary"
              value = "<?php echo isset($listing['salary']) ? $listing['salary'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="requirement"
              placeholder="Requirements"
              value = "<?php echo isset($listing['requirement']) ? $listing['requirement'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="benefits"
              placeholder="Benefits"
              value = "<?php echo isset($listing['benefits']) ? $listing['benefits'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="tags"
              placeholder="Tags"
              value = "<?php echo isset($listing['tags']) ? $listing['tags'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Company Info & Location
          </h2>
          <div class="mb-4">
            <input
              type="text"
              name="company"
              placeholder="Company Name"
              value = "<?php echo isset($listing['company']) ? $listing['company'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="address"
              placeholder="Address"
              value = "<?php echo isset($listing['address']) ? $listing['address'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="city"
              placeholder="City"
              value = "<?php echo isset($listing['city']) ? $listing['city'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="state"
              placeholder="State"
              value = "<?php echo isset($listing['state']) ? $listing['state'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="phone"
              placeholder="Phone"
              value = "<?php echo isset($listing['phone']) ? $listing['phone'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="email"
              name="email"
              placeholder="Email Address For Applications"
              value = "<?php echo isset($listing['email']) ? $listing['email'] : '';?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
            Save
          </button>
          <a
            href="/"
            class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none"
          >
            Cancel
          </a>
        </form>
      </div>
    </section>
	<?php
	loadPartials("footer");
	?>