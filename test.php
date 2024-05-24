<?php

// Define the getUpcomingAvailability function once (outside any code blocks)
function getUpcomingAvailability() {
  $days = array("monday", "wednesday", "friday");
  $timeSlots = array(
    "10:00-13:00",
    "13:00-16:00",
    "16:00-19:00"
  );
  $availability = array();
  $currentTime = time();

  // Add 24 hours to current time
  $futureTime = $currentTime + (24 * 60 * 60);

  for ($i = 0; $i < 3; $i++) {
    $targetDay = $days[$i];

    // Get the timestamp for the next occurrence of the target day
    $nextTargetDate = strtotime("next " . $targetDay, $futureTime);

    // Check if the time after 24 hours is greater than 4:00 PM
    if (date("H", $nextTargetDate) < 16) {
      $date = date("Y-m-d", $nextTargetDate);
      $availableSlots = getAvailableTimeSlots($date);
      $availability[] = array(
        'date' => $date,
        'time_slot' => $availableSlots
      );
    } else {
      // Skip this date and move to the next day of the week
      $futureTime = strtotime("next monday", $nextTargetDate);
    }
  }
  return $availability;
}

// Function to get available time slots for a given date (dummy implementation)
function getAvailableTimeSlots($date) {
  // In a real application, you would fetch available time slots for the given date
  // This is just a placeholder implementation
  return array(
    "10:00-13:00",
    "13:00-16:00",
    "16:00-19:00"
  );
}

// Function to remove duplicate dates and keep only unique ones
function removeDuplicateDates($availability) {
  $uniqueDates = [];
  foreach ($availability as $availableItem) {
    $date = $availableItem['date'];
    if (!in_array($date, $uniqueDates)) {
      $uniqueDates[] = $date;
    }
  }
  return $uniqueDates;
}

// Get upcoming availability
$availability = getUpcomingAvailability();

// Filter out duplicate dates
$uniqueDates = removeDuplicateDates($availability);

// Get selected date
$selectedDate = isset($_POST['day']) ? $_POST['day'] : (isset($uniqueDates[0]) ? $uniqueDates[0] : null);
?>

<div class="container1">
  <div class="left-container">
    <h2 id="order-summary-title">Order Summary</h2>
    <div class="order-details">
    </div>
  </div>

  <div class="right-container">
    <form class="top-form" id="pickup-form" name="pickup-form" method="POST" action="">
      <h3>Select Pick Up Time and Date:</h3>
      <label for="day">Pick a Day:</label>
      <select id="day" name="day">
        <?php foreach ($uniqueDates as $date) : ?>
          <option value="<?php echo $date; ?>" <?php if ($selectedDate == $date) echo 'selected'; ?>>
            <?php echo date('l', strtotime($date)); ?>, 
            <?php echo $date; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <label>Time Slots:</label><br>
      <?php if ($selectedDate) : ?>
        <?php foreach ($availability as $availableItem) : ?>
          <?php if ($availableItem['date'] == $selectedDate) : ?>
            <?php foreach ($availableItem['time_slot'] as $slot) : ?>
              <?php
                $start = new DateTime(explode("-", $slot)[0]);
                $end = new DateTime(explode("-", $slot)[1]);
                $slotLabel = $start->format('H:i') . ' - ' . $end->format('H:i');
                $radioId = 'time-' . str_replace(':', '-', $slotLabel); // Unique ID
              ?>
              <label for="<?php echo $radioId; ?>">
                <input type="radio" id="<?php echo $radioId; ?>" name="time" value="<?php echo $slotLabel; ?>">
                <?php echo $slotLabel; ?>
              </label><br>
            <?php endforeach; ?>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endif; ?>
      <br>
      <label for="location">Pick a Location:</label>
      <select id="location" name="location">
        <option value="Location 1">Location 1</option>
        <option value="Location 2">Location 2</option>
        <option value="Location 3">Location 3</option>
      </select>
      <button type="submit" name="submit">Submit</button>
    </form>
  </div>
</div>
