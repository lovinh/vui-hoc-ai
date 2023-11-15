// Add your custom scripts here
document.getElementById("noteButton").addEventListener("click", function () {
  var sidebar = document.getElementById("noteSidebar");
  if (sidebar.style.display === "none") {
    sidebar.style.display = "block";
  } else {
    sidebar.style.display = "none";
  }
});
