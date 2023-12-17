console.log("Hello, world");
ClassicEditor.create(document.querySelector("#course_description")).catch(
  (error) => {
    console.error(error);
  }
);
ClassicEditor.create(
  document.querySelector("#course_update_description")
).catch((error) => {
  console.error(error);
});
