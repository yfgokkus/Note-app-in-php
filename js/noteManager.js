var url = "http://localhost/note-app/";

function addNote() {
  var title = $("#addNoteForm").find("#title").val().trim();
  var desc = $("#addNoteForm").find("#desc").val().trim();

  if (title === "" && desc === "") {
    return;
  }

  var data = $("#addNoteForm").serialize() + "&action=add";
  $.ajax({
    type: "POST",
    url: url + "data/noteManager.php",
    data: data,
    dataType: "json",
    success: function (response) {
      if (response.header == "ok") {
        var newNote = `
          <div class="card shadow-sm mb-3" style="border: none;" id="note-${
            response.noteId
          }">
                    <div class="card-body pb-2">
                        <h3 class="card-title m-0">${title}</h3>

                        <p class="card-text my-2" style="max-width: 100%; height: 3rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                        ${desc}
                        </p>
                        <div class="d-flex justify-content-between">
                            <h6 class="card-date card-subtitle text-muted m-0">${getCurrentDateTime()}</h6>
                            <div>
                                <a href="javascript:void(0);" class="card-link" data-toggle="modal" data-target="#modifyNoteModal" onclick="review(${
                                  response.noteId
                                })"><i class="fa-solid fa-eye" style="color: #2ea4ff;"></i></a>
                                <a href="javascript:void(0);" class="card-link mr-2" onclick="deleteNote(${
                                  response.noteId
                                })"><i class="fa-solid fa-trash" style="color: #2ea4ff;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
        `;

        $("#notesContainer").append(newNote);

        $("#addNoteForm")[0].reset();
        $("#addNoteModal").modal("hide");
      } else if (response.header == "error") {
        Swal.fire({
          icon: "error",
          title: "HATA",
          text: response.message,
          customClass: {
            popup: "swal-popup-custom",
          },
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("Error occurred:", textStatus, errorThrown);
      console.log("Response Text:", jqXHR.responseText);
    },
  });
}

function deleteNote(noteId) {
  Swal.fire({
    title: "Are you sure?",
    text: "This action will delete the note.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: url + "data/noteManager.php",
        data: { action: "delete", note_id: noteId },
        dataType: "json",
        success: function (response) {
          if (response.header === "ok") {
            $("#note-" + noteId).remove();
            Swal.fire("Deleted!", "Your note has been deleted.", "success");
          } else if (response.header === "error") {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: response.message,
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log("Error occurred:", textStatus, errorThrown);
          console.log("Response Text:", jqXHR.responseText);
        },
      });
    }
  });
}

function review(noteId) {
  const button = document.getElementById("modifyNoteButton");

  const noteCard = $("#note-" + noteId);
  const currentTitle = noteCard.find(".card-title").text().trim();
  const currentDesc = noteCard.find(".card-text").text().trim();

  $("#modifyNoteForm").find("#title").val(currentTitle);
  $("#modifyNoteForm").find("#desc").val(currentDesc);

  button.onclick = () => modifyNote(noteId);
  console.log("click event eklendi");
}

function modifyNote(noteId) {
  console.log("click event ", noteId, " numaralı not için çalıştı.");

  var title = $("#modifyNoteForm").find("#title").val().trim();
  var desc = $("#modifyNoteForm").find("#desc").val().trim();

  var noteCard = $("#note-" + noteId);
  var currentTitle = noteCard.find(".card-title").text().trim();
  var currentDesc = noteCard.find(".card-text").text().trim();

  if (title === currentTitle && desc === currentDesc) {
    $("#modifyNoteForm")[0].reset();
    $("#modifyNoteModal").modal("hide");
    return;
  }

  if (title === "" && desc === "") {
    Swal.fire({
      icon: "warning",
      title: "Warning",
      text: "Two fields cannot be left blank at the same time.",
      customClass: {
        popup: "swal-popup-custom",
      },
    });
    return;
  }

  var data = {
    title: title,
    desc: desc,
    noteId: noteId,
    action: "modify",
  };

  $.ajax({
    type: "POST",
    url: url + "data/noteManager.php",
    data: data,
    dataType: "json",
    success: function (response) {
      if (response.header == "ok") {
        var noteCard = $("#note-" + noteId);
        noteCard.find(".card-title").text(title);
        noteCard.find(".card-text").text(desc);

        var currentDate = getCurrentDateTime();
        noteCard
          .find(".card-date")
          .html(
            currentDate + ' <span class="badge badge-secondary">modified</span>'
          );

        $("#modifyNoteForm")[0].reset();
        $("#modifyNoteModal").modal("hide");

        Swal.fire("Modified!", "Your note has been updated.", "success");
      } else if (response.header == "error") {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: response.message,
          customClass: {
            popup: "swal-popup-custom",
          },
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("Error occurred:", textStatus, errorThrown);
      console.log("Response Text:", jqXHR.responseText);
    },
  });
}

function getCurrentDateTime() {
  var today = new Date();

  var day = String(today.getDate()).padStart(2, "0");
  var month = String(today.getMonth() + 1).padStart(2, "0");
  var year = String(today.getFullYear()).slice(-2);

  var hours = String(today.getHours()).padStart(2, "0");
  var minutes = String(today.getMinutes()).padStart(2, "0");

  return day + "." + month + "." + year + " " + hours + ":" + minutes;
}
