document.addEventListener('DOMContentLoaded',function(){
    const editButton=document.getElementById('editButton');
    const tableBody=document.getElementById('courseTable');

    console.log(editButton);
    console.log(tableBody);

    if(editButton && tableBody){
    editButton.addEventListener('click',()=>{
        const rows=tableBody.querySelectorAll('tr');
        rows.forEach(row=>{
            const cellData=row.querySelectorAll('td');
            cellData.forEach((cellData,index)=>{
                if(index!==0){
                    cellData.setAttribute('contenteditable','true');
                    cellData.classList.add('editable');
                }
            });
        });
    });
    tableBody.addEventListener('input',()=>{
        const rows=tableBody.querySelectorAll('tr');
        rows.forEach(row=>{
            const cellData=row.querySelectorAll('td');
        });
    });
}else{
    console.error("Edit table or body is not working.");
}
});