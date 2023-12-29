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
ClassicEditor.create(document.querySelector("#lesson_description")).catch(
  (error) => {
    console.error(error);
  }
);
ClassicEditor.create(document.querySelector("#section_content")).catch(
  (error) => {
    console.error(error);
  }
);

console.log("Editor:");
console.log(editor);
