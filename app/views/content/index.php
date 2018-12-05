<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-3"><?php echo $data['title']; ?></h1>
            <div class="container mb-3" id="three-container"></div>
            <br>
            
            <div class="row justify-content-md-center">
                <div class="col-md-5"><input class="form-control"id="snapId" type="text" /></div>
                <div class="col-md-1"><button class="btn btn-danger"id="theButton">Verify</button></div>
            </div>
            
            <div class="content_loading display-4 mt-3" ><div id="load_percentage">0%</div> <p>Loading...</p></div>

            <ul id="template" class="list-group justify-content-md-center"></ul>
    

            <p class="lead"><?php echo $data['description']; ?></p>
            <div class="loading_container"><img src="assets/deadline.gif" alt="loading"></div>
            
            <div id="loadContent"class="btn btn-danger btn-block mb-1">Load</div>
            <form action="<?php echo URLROOT;?>/contents/upload" method="post">
                 <input type="submit" value="Upload" class="btn btn-danger btn-block mb-1">
            </form> 
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>


<script>
// let appId = "2066.663df20f-f401-479f-9639-57e28e349386";
// let snapId = "2064.e5542544-ceba-4750-8955-ea0179c6bf34";
// let processId = "1.b97d9c2a-1276-4c0e-9e96-ad49c83321d4";
// let stepId = "62b4e1b9-553e-cf42-820c-b3a27f969bd9";
let theObject = [];
let selection = "";
$.ajax({
  method: "GET",
  url:
    "SOME API",
  xhrFields: {
    withCredentials: true
  }
}).then(result => {
  result.forEach(item => {
    selection += `
      <button onClick="loadIt(event)" class="btn btn-lg btn-primary" value="${
        item.id
      }">${item.name}</button>
  `;
  });
  document.getElementById("three-container").innerHTML = selection;
});

const loadIt = e => {
    document.querySelector(".lead").innerHTML = 'Load Content';
  document.querySelector("#snapId").value = e.target.value;
};
const getIt = () => {
  
  let snapId = document.querySelector("#snapId").value;
if(snapId){
    const appId = "2066.663df20f-f401-479f-9639-57e28e349386";
    //const appId = "2066.4bfd9aec-f58a-431a-a7c9-47bc314d116a"; //FOPS
    document.querySelector(".content_loading").style.display = "block";
    $.ajax({
    method: "GET",
    url: `SOME API`,
    xhrFields: {
      withCredentials: true
    },
    success: function(data) {
      //console.log(data);
      
      const allBlocks = data.map(item => item.id);
      const dict = [];
      data.map(item => {
        return (dict[item.id] = item.name);
      });

      const promiseChain = [];
      for (let i = 0; i < allBlocks.length; i++) {
        promiseChain.push(
          getData(
            "GET",
            true,
            `SOME API`
          ).then(data => {
            console.log(`${allBlocks[i]} is done`);
            let number = Math.round(((i + 1) / allBlocks.length) * 100);
            document.querySelector("#load_percentage").textContent = `${number}%`;
            
            return JSON.parse(data);
          })
        );
      }
      Promise.all(promiseChain)
        .then(datas => {
          let result = [];
          const origin = [];
          datas.forEach(data => {
            result.push(...data);
          });
          const final = [];
          let snapshotName = "";
          result.forEach(element => {
            if (element.stepTypeId != null) {
              snapshotName = element.snapshotName;
              let newItem = {
                blockName: dict[element.subProcessId],
                contentId: element.stepUniqueId,
                stepName: element.stepName,
                stepId: element.bpguid,
                snapshot: element.snapshotName,
                snapshotId: snapId,
                blockId: element.subProcessId,
                overlay: element.helpOverlay,
                isMarkdown: element.isMarkdown ? 1 : 0,
                options: element.options.map(item => {
                  return {
                    option_id: item.id,
                    signal: item.signal,
                    titleEn: item.shortDescE,
                    titleFr: item.shortDescF,
                    bodyEn: element.isMarkdown
                      ? item.content.description.en
                      : atob(item.longDescE),
                    bodyFr: element.isMarkdown
                      ? item.content.description.fr
                      : atob(item.longDescF),
                    infoTitleEn: item.infoTitleEn,
                    infoTitleFr: item.infoTitleFr,
                    infoLongDesE: element.isMarkdown
                      ? item.content.info.en
                        ? item.content.info.en
                        : ""
                      : item.infoDescriptionEn
                        ? atob(item.infoDescriptionEn)
                        : "",
                    infoLongDesF: element.isMarkdown
                      ? item.content.info.fr
                        ? item.content.info.fr
                        : ""
                      : item.infoDescriptionFr
                        ? atob(item.infoDescriptionFr)
                        : ""
                  };
                }),
                titleEn: element.shortDescE,
                titleFr: element.shortDescF,
                bodyEn: element.isMarkdown
                  ? element.content.description.en
                    ? element.content.description.en
                    : ""
                  : element.longDescE
                    ? atob(element.longDescE)
                    : "",
                bodyFr: element.isMarkdown
                  ? element.content.description.fr
                    ? element.content.description.fr
                    : ""
                  : element.longDescF
                    ? atob(element.longDescF)
                    : ""
              };
              final.push(newItem);
              origin.push(element);
            }
          });
          const blob = new Blob([JSON.stringify(final)], {
            type: "application/json"
          });
          theObject = final;
          const blob2 = new Blob([JSON.stringify(origin)], {
            type: "application/json"
          });
          const url = URL.createObjectURL(blob);
          const url2 = URL.createObjectURL(blob2);
          const lists = `<li class="list-group-item"><a href=${url}>Download ${snapshotName}_fullContent.json</a></li>
                                <li class="list-group-item"><a href=${url2}>Download ${snapshotName}_origin.json</a></li>`;

          
          document.getElementById("template").innerHTML = lists;
          document.querySelector(".content_loading").style.display = "none";
          document.querySelectorAll(".btn").disabled = false;
        })
        .catch(err => console.log(err));
    },
    error: function(error) {
      console.log("Error");
      console.log(error);
    }
  });
}else{
    document.querySelector(".lead").innerHTML = 'Pick a snapshot!';
}

  
};

const getData = (method, cred, url) => {
  return new Promise((resolve, reject) => {
    let xhr = new XMLHttpRequest();
    xhr.withCredentials = cred;
    xhr.open(method, url, true);
    xhr.onload = function() {
      if (this.status >= 200 && this.status < 300) {
        resolve(xhr.response);
      } else {
        reject({
          status: this.status,
          statusText: xhr.statusText
        });
      }
    };
    xhr.onerror = function() {
      reject({
        status: this.status,
        statusText: xhr.statusText
      });
    };
    xhr.send();
  });
};

const loadContent = () => {
    document.querySelector(".lead").innerHTML = 'Load Content';
    if(theObject.length !== 0){
      document.querySelector(".loading_container").style.display = 'block';
        $.ajax({
        type: 'POST',
        url: '<?php echo URLROOT;?>/contents/load',
        data: {json: JSON.stringify(theObject)},
        dataType: 'json',
        success: function(res){
            console.log(res);
        },
        complete: function(){ document.querySelector(".loading_container").style.display = 'none';}
         
        });
    }else{
        document.querySelector(".lead").innerHTML = 'Verify Content First!';
    }
    
}

document.getElementById("loadContent").addEventListener("click", loadContent);
document.getElementById("theButton").addEventListener("click", getIt);




</script>