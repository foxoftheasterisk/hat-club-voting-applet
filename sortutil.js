
{
    let lastSort = null;

    function sortBy(header)
    {
        let table = header.parentNode.parentNode;

        if (lastSort == header)
        {
            //it's already sorted by this column, we just want to switch it between asc and desc
            flipTable(table);
        }
        lastSort = header;
        
        let column = 0;
        {
            let currentItem = header;
            while(currentItem.previousElementSibling != null)
            {
                column++;
                currentItem = currentItem.previousElementSibling;
            }                
        }
        
        if (! header.hasAttribute("data-sorttype")) {
            throw new Error("No sort type defined!")
        }
        
        let comesBeforeFunction;
        switch ( header.getAttribute("data-sorttype").toLowerCase())
        {
            case "text":
                comesBeforeFunction = comesBeforeText;
                break;
            case "number":
                comesBeforeFunction = comesBeforeNumber;
                break;
            case "numberdesc":
                comesBeforeFunction = comesBeforeNumberDesc;
                break;
            default:
                throw new Error("Unrecognized sort method: " + header.getAttribute("data-sorttype"));
        }
        
        //assuming that row 0 is headers and therefore should not be sorted
        //could probably make it more robust and ignore all rows in which element [column] is a <th>...
        //but why bother??
        
        mergeSort(table, column, 1, table.rows.length - 1, comesBeforeFunction);
        
    }
    
    //true if valueA sorts earlier or equal, false otherwise
    //no conversion is needed before passing in to these functions
    function comesBeforeText(valueA, valueB)
    {
        let comparableA = valueA.toLowerCase();
        let comparableB = valueB.toLowerCase();
        
        return (comparableA <= comparableB);
    }
    
    function comesBeforeNumber(valueA, valueB)
    {
        let comparableA = Number(valueA);
        let comparableB = Number(valueB);
        
        return (comparableA <= comparableB);
    }
    
    function comesBeforeNumberDesc(valueA, valueB)
    {
        let comparableA = Number(valueA);
        let comparableB = Number(valueB);
        
        return (comparableA >= comparableB);
    }
    
    //fuck it. merge sort.
    //merge sort in place, which is... weird. but doable.
    function mergeSort(table, column, firstRow, lastRow, comesBefore)
    {
        if (firstRow > lastRow)
        {
            throw new Error("Table is empty or invalid argument order");
        }
        let diff = lastRow - firstRow;
        if (diff == 0)
            return;  //a single item is always sorted
        
        let midRow = firstRow + Math.floor(diff / 2);
        mergeSort(table, column, firstRow, midRow, comesBefore);
        mergeSort(table, column, midRow + 1, lastRow, comesBefore);
        
        let i = firstRow;
        let j = midRow + 1;
        
        while( i < j && j <= lastRow ) //i. think that's right? TODO: make sure
        {
            //extract values
            let iCell = table.rows[i].cells[column];
            let jCell = table.rows[j].cells[column];
            
            let iValue, jValue;
            
            if (iCell.hasAttribute("data-sortvalue")) {
                iValue = iCell.getAttribute("data-sortvalue");
            }
            else {
                iValue = iCell.innerHTML;
            }
            
            if (jCell.hasAttribute("data-sortvalue")) {
                jValue = jCell.getAttribute("data-sortvalue");
            }
            else {
                jValue = jCell.innerHTML;
            }
            
            //compare
            if (comesBefore(iValue, jValue)) {
                //we would append row i to the sorted area (firstRow->i-1)
                //however... it's already the next row. this would be a no-op!
                //so, we just increment i.
                i++;
            }
            else {
                //here, we actually append.
                //i am assured that insertBefore removes the element from its previous Location
                //if this is not always the case, blame w3schools.
                table.insertBefore(table.rows[j], table.rows[i]);
                
                //we must increment both:
                //j, to advance to the next row;
                //i, because we just added a row before the item i was just pointing to
                i++;
                j++;
                
                //that does mean we increment i either way and COULD use a for loop,
                //but that would be confusing.
            }
        }
        
        return; //unnecessary, but idc
    }

    function flipTable(table)
    {
        
    }
}

function makeSortablesSortable() {
    let sortables = document.getElementsByClassName("sortable");
    for (let sortable of sortables)
    {
        sortable.addEventListener("click", 
                                  function() {sortBy(sortable);} );
    }
}