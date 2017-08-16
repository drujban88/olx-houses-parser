$(()=>{
  $('tr').click((e)=>{
    window.open(
      $(e.currentTarget.children[0].children).attr('href'),
      '_blank'
    );
  });
});
