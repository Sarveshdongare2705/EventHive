function initializeCalendar() {
  const currentDate = new Date();
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth() + 1;
  const formattedDate = `${year}-${month.toString().padStart(2, "0")}`;
  document.getElementById("month").value = formattedDate;
  generateCalendar();
}
async function fetchEventsForDate(date) {
  const response = await fetch(`home.php?date=${date}`);
  const events = await response.json();
  return events;
}
async function generateCalendar() {
  const calendarContainer = document.getElementById("calendar");
  calendarContainer.innerHTML = "";
  const selectedDate = new Date(document.getElementById("month").value);
  const year = selectedDate.getFullYear();
  const month = selectedDate.getMonth();
  const firstDayOfMonth = new Date(year, month, 1);
  const lastDayOfMonth = new Date(year, month + 1, 0);
  const daysInMonth = lastDayOfMonth.getDate();
  const startingDay = firstDayOfMonth.getDay();
  const todayDate = new Date();

  const DOB = document.getElementById("dob");
  const dobValue = DOB.textContent.trim();
  const dob = new Date(dobValue);
  console.log(dobValue, dob);
  const dobMonth = dob.getMonth();
  const dobDay = dob.getDate();

  let index = 0;
  for (let i = 0; i < 35; i++) {
    const day = i - startingDay + 1;
    const currentDate = new Date(year, month, day);
    const calendarDay = document.createElement("div");
    if (day > 0 && day <= daysInMonth) {
      calendarDay.textContent = day;
      calendarDay.classList.add("calendar-day");
      console.log(day, dobDay, month, dobMonth);
      if (day == dobDay && month == dobMonth) {
        const birthdayText = document.createElement("span");
        birthdayText.textContent = "Happy Birthday!";
        birthdayText.style.color = "coral";
        birthdayText.style.fontSize = "12px";
        calendarDay.appendChild(birthdayText);
      }

      const dateString = `${year}-${(month + 1)
        .toString()
        .padStart(2, "0")}-${day.toString().padStart(2, "0")}`;
      const events = await fetchEventsForDate(dateString);
      if (events.length > 0) {
        const eventList = document.createElement("span");
        eventList.classList.add("events");
        events.forEach((event) => {
          const eventItem = document.createElement("span");
          eventItem.textContent = event.title;
          eventItem.classList.add("event");
          const colorIndex = index % eventColors.length;
          eventItem.style.backgroundColor = eventColors[colorIndex];
          eventItem.addEventListener("click", () => {
            displayEventModal(event, eventColors[colorIndex]);
          });
          eventList.appendChild(eventItem);
          index++;
        });
        calendarDay.appendChild(eventList);
      }
    }
    if (currentDate.toDateString() === todayDate.toDateString()) {
      calendarDay.classList.add("current-date");
    }
    calendarContainer.appendChild(calendarDay);
  }
}
const eventColors = [
  "lightred",
  "yellow",
  "lightgreen",
  "orange",
  "#0077be",
  "violet",
  "pink",
  "gray",
  "lightblue",
  "coral",
];

function displayEventModal(event, color) {
  const modal = document.createElement("div");
  modal.classList.add("modal");

  const titleInput = document.createElement("input");
  titleInput.type = "text";
  titleInput.classList.add("title");
  titleInput.value = event.title;

  const descriptionTextarea = document.createElement("textarea");
  descriptionTextarea.classList.add("desc");
  descriptionTextarea.textContent = event.desc;

  const eventDateInput = document.createElement("input");
  eventDateInput.type = "date";
  eventDateInput.value = event.event_date;

  const updateButton = document.createElement("button");
  updateButton.classList.add("update");
  updateButton.style.color = "black";
  updateButton.style.backgroundColor = color;
  updateButton.style.borderColor = color;
  updateButton.textContent = "Update";
  updateButton.addEventListener("click", () => {
    const updatedEvent = {
      id: event.id,
      title: titleInput.value,
      desc: descriptionTextarea.value,
      event_date: eventDateInput.value,
    };
    updateEvent(updatedEvent);
    modal.remove();
  });

  const deleteButton = document.createElement("button");
  updateButton.classList.add("delete");
  deleteButton.style.color = color;
  deleteButton.style.backgroundColor = "black";

  deleteButton.textContent = "Delete";
  deleteButton.addEventListener("click", () => {
    deleteEvent(event.id);
    modal.remove();
  });

  const closeButton = document.createElement("span");
  closeButton.textContent = "x";
  closeButton.classList.add("close-modal");
  closeButton.addEventListener("click", () => {
    modal.remove();
  });

  modal.appendChild(closeButton);
  modal.appendChild(titleInput);
  modal.appendChild(descriptionTextarea);
  modal.appendChild(eventDateInput);
  modal.appendChild(updateButton);
  modal.appendChild(deleteButton);

  document.body.appendChild(modal);
}

function updateEvent(event) {
  fetch("update_event.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(event),
  })
    .then((response) => {
      if (response.ok) {
        console.log("Event updated successfully");
        location.reload();
      } else {
        throw new Error("Failed to update event");
      }
    })
    .catch((error) => {
      console.error("Error updating event:", error);
    });
}

function deleteEvent(eventId) {
  fetch("delete_event.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: eventId }),
  })
    .then((response) => {
      if (response.ok) {
        console.log("Event deleted successfully");
        location.reload();
      } else {
        throw new Error("Failed to delete event");
      }
    })
    .catch((error) => {
      console.error("Error deleting event:", error);
    });
}
