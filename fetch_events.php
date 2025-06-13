<?php
include "./DB/database.php";

$search = $_POST['search'] ?? '';
$category = $_POST['category'] ?? '';
$status = $_POST['status'] ?? '';

$query = "SELECT * FROM events WHERE 1=1";

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " AND title LIKE '%$search%'";
}

if (!empty($category)) {
    $category = mysqli_real_escape_string($conn, $category);
    $query .= " AND category = '$category'";
}

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $query .= " AND status = '$status'";
}

$query .= " ORDER BY date ASC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    echo "<p class='text-center col-span-3 text-gray-500'>No events found.</p>";
}

while ($row = mysqli_fetch_assoc($result)) {
?>
  <a href="event-details.php?id=<?= $row['id'] ?>" class="block hover:shadow-lg transition-shadow duration-300">
  <!-- entire card inside -->
   <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="relative">
      
      
      <?php if ($row['status'] === 'live'): ?>
        <span class="absolute top-2 right-2 bg-black text-white text-xs px-2 py-1 rounded font-bold p-2">🔴 Live Now</span>
      <?php endif; ?>
      <?php if ($row['status'] === 'upcoming'): ?>
        <span class="absolute top-2 right-2 bg-black text-white text-xs px-2 py-1 rounded font-bold p-2">⌛ Upcoming</span>
      <?php endif; ?>
      <?php if ($row['status'] === 'past'): ?>
        <span class="absolute top-2 right-2 bg-black text-white text-xs px-2 py-1 rounded font-bold p-2">🔥 Ended</span>
      <?php endif; ?>

      <img src="<?= $row['image'] ?>" class="w-full h-48 object-cover" alt="<?= htmlspecialchars($row['title']) ?>">
      
    </div>
    <div class="p-4">
      <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold"><?= htmlspecialchars($row['title']) ?></h2>
      <span class=" inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-green-600/20 ring-inset"><?php echo $row['category']?></span>

      </div>
      <p class="text-sm text-gray-600"><?= htmlspecialchars($row['location']) ?></p>
      <div class=" text-sm text-gray-700 mt-2">
        <span>📅 <?= date("j M, Y", strtotime($row['date'])) ?></span>
        <span>🕜 <?= date("g:i A", strtotime($row['time'])) ?></span>
      </div>
      <div class="mt-2 text-green-600 font-medium">⭐ Starts from ৳<?= htmlspecialchars($row['price']) ?></div>
    </div>
  </div>
</a>

  
<?php } ?>
<script src="https://kit.fontawesome.com/c233ed958f.js" crossorigin="anonymous"></script>
