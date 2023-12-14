console.log("Hello, world");
ClassicEditor.create(document.querySelector("#note_content")).catch((error) => {
  console.error(error);
});
