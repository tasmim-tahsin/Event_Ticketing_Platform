<?php 
session_start();
include "./navbar.php";
include "./DB/database.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Events</title>
  <link href="./output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="text-center mt-6 bg-black text-white max-w-6xl mx-auto rounded-md py-3">
        <h1 class="text-3xl font-bold">Explore Live Events!</h1>
        <h3 class="text-lg mt-3">Explore the Universe of Events at Your Fingertips.</h3>
    </div>
<div class="max-w-6xl mx-auto p-6">
  <!-- Filters -->
  <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 bg-white rounded-md p-6 ">
    <input type="text" id="search" placeholder="Search events..."
           class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/3">
    
    <select id="category" class="border border-gray-300 rounded px-4 py-2">
      <option value="">All Categories</option>
      <option value="General">General</option>
      <option value="Music">Music</option>
      <option value="Tech">Tech</option>
      <option value="Exhibition">Exhibition</option>
      <option value="Sports">Sports</option>
      <option value="Workshop">Workshop</option>
      <option value="Conference">Conference</option>
      <option value="Job Fair">Job Fair</option>
      <option value="Seminar">Seminar</option>
      <!-- Add more as needed -->
    </select>

    <div class="flex gap-4 items-center text-sm">
      <label><input type="radio" name="status" value="" class="radio radio-success" checked> All</label>
      <label><input type="radio" name="status" value="live" class="radio radio-success"> Live</label>
      <label><input type="radio" name="status" value="upcoming" class="radio radio-info"> Upcoming</label>
    </div>
  </div>

  <!-- Event Cards -->
  <div id="events-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- AJAX loaded content -->
  </div>
</div>
<?php
    include "./footer.php";
?>

<script>
  function fetchEvents() {
    const search = document.getElementById("search").value;
    const category = document.getElementById("category").value;
    const status = document.querySelector("input[name='status']:checked").value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_events.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (this.status === 200) {
        document.getElementById("events-container").innerHTML = this.responseText;
      }
    };
    xhr.send(`search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}&status=${encodeURIComponent(status)}`);
  }

  // Events
  document.getElementById("search").addEventListener("input", fetchEvents);
  document.getElementById("category").addEventListener("change", fetchEvents);
  document.querySelectorAll("input[name='status']").forEach(radio =>
    radio.addEventListener("change", fetchEvents)
  );

  // Initial load
  fetchEvents();
</script>
</body>
</html>
