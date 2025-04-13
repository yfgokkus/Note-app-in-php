var url = "http://localhost/note-app/";

function register() {
  console.log("REGISTER BUTON ÇALIŞTI.");
  var data = $("#registerForm").serialize() + "&action=register";
  $.ajax({
    type: "POST",
    url: url + "data/userManager.php",
    data: data,
    success: function (response) {
      if (response.header === "empty") {
        Swal.fire({
          icon: "warning",
          title: "Kayıt başarısız.",
          text: "Lütfen boş alan bırakmayınız.",
          customClass: {
            popup: "swal-popup-custom",
          },
        });
      } else if (response.header === "format") {
        Swal.fire({
          icon: "warning",
          title: "Kayıt başarısız.",
          text: "Bilgileri yanlış formatta girdiniz.",
          customClass: {
            popup: "swal-popup-custom",
          },
        });
      } else if (response.header === "match") {
        Swal.fire({
          icon: "warning",
          title: "Kayıt başarısız.",
          text: "Parolalar eşleşmiyor.",
          customClass: {
            popup: "swal-popup-custom",
          },
        });
      } else if (response.header === "already") {
        Swal.fire({
          icon: "warning",
          title: "Kayıt başarısız.",
          text: "Bu kullanıcı zaten sisteme kayıtlı.",
          customClass: {
            popup: "swal-popup-custom",
          },
        });
      } else if (response.header === "error") {
        Swal.fire({
          icon: "error",
          title: "Kayıt başarısız.",
          text: "Bir hata oluştu.",
          customClass: {
            popup: "swal-popup-custom",
          },
        });
      } else if (response.header === "ok") {
        Swal.fire({
          icon: "success",
          title: "Kayıt başarılı.",
          text: "Giriş yapabilirsiniz.",
          customClass: {
            popup: "swal-popup-custom",
          },
        });
        $("#registerModal").modal("hide");
      }
    },
  });
}

function login() {
  var data = $("#loginForm").serialize() + "&action=login";
  $.ajax({
    type: "POST",
    url: url + "data/userManager.php",
    data: data,
    success: function (response) {
      try {
        if (response.header === "wrong") {
          Swal.fire({
            icon: "warning",
            title: "Giriş başarısız.",
            text: "Kullanıcı adı veya şifre geçersiz.",
            customClass: {
              popup: "swal-popup-custom",
            },
          });
        } else if (response.header === "error") {
          Swal.fire({
            icon: "error",
            title: "Giriş başarısız.",
            text: response.message,
            customClass: {
              popup: "swal-popup-custom",
            },
          });
        } else if (response.header === "ok") {
          window.location.href = url + "index.php";
        }
      } catch (e) {
        console.error("Parsing error:", e, "result: ", result);
      }
    },
  });
}

function logout() {
  $.ajax({
    type: "POST",
    url: "data/userManager.php",
    data: { action: "logout" },
    success: function (response) {
      if (response.header === "ok") {
        window.location.href = "pages/loginPage.php";
      } else {
        alert("Error logging out.");
      }
    },
    error: function () {
      alert("Error logging out.");
    },
  });
}
