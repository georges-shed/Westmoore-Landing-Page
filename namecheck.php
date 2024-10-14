<?php
include 'reserved.php'; // Include the reserved keywords file

echo '<style>
.response-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #28a745;
  color: white;
  padding: 25px;
  margin-top: 20px;
  border-radius: 10px;
  text-align: center;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  max-width: 600px; /* Increased width */
  margin-left: auto;
  margin-right: auto;
}

.response-box img {
  width: 50px;
  height: 50px;
  margin-bottom: 15px;
}

.response-box h2 {
  font-size: 24px; /* Increased title size */
  margin: 0;
  color:white;
}

.response-box p {
  font-size: 16px; /* Increased paragraph size */
  margin: 10px 0 0;
  color:white;
}

.choose-package-btn {
  padding: 12px 24px; /* Increased button padding */
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 15px;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.choose-package-btn:hover {
  background-color: #218838;
}

#buttonContainer {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

#buttonContainer button {
  padding: 10px 20px;
  background-color: #E67000;
  color: white;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  margin: 0 5px;
  flex: 1;
  max-width: 120px;
}

#buttonContainer .active {
  background-color: #E67000;
  color: white;
}

#buttonContainer button:not(.active) {
  background-color: transparent;
  color: #E67000;
  border: 1px solid #E67000;
}

#formContainer {
  display: flex;
  justify-content: center;
  width: 100%;
}

#formContainer form {
  display: flex;
  align-items: center;
  border-radius: 50px;
  background-color: white;
  padding: 0;
  width: 90%;
  max-width: 600px; /* Increased width */
  margin: auto;
  border: 1px solid #E67000;
}

#formContainer form input[type="text"] {
  flex: 1;
  border: none;
  padding: 16px 24px; /* Increased input padding */
  border-radius: 50px 0 0 50px;
  outline: none;
  font-size: 16px;
}

#formContainer form button[type="submit"] {
  padding: 16px 24px; /* Increased button padding */
  background-color: #E67000;
  color: white;
  border: none;
  border-radius: 0 50px 50px 0;
  cursor: pointer;
  font-size: 16px;
}

.uk {
  padding: 10px 20px;
}

/* Media query for screens below 500px */
@media (max-width: 500px) {
  #buttonContainer button {
    padding: 8px 16px;
    font-size: 13px;
    max-width: 90px;
  }

  #formContainer form {
    width: 95%;
    max-width: 320px;
  }

  #formContainer form input[type="text"],
  #formContainer form button[type="submit"] {
    padding: 14px 16px;
    font-size: 14px;
  }
  
  .response-box {
    width: 95%;
    max-width: 320px;
  }

  .response-box h2 {
    font-size: 22px;
    color:white;
  }

  .response-box p {
    font-size: 14px;
    color:white;
  }

  .choose-package-btn {
    padding: 10px 18px;
    font-size: 14px;
  }
}
</style>

';

// Display button container and include scripts only once on initial page load
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo '<div id="buttonContainer" style="text-align: center; margin-bottom: 20px;">
            <button onclick="loadForm(\'UK\')" class="active uk">UK</button>
            <button onclick="loadForm(\'Ireland\')">Ireland</button>
          </div>';

    echo '<div id="formContainer" style="margin:auto;margin-bottom: 20px; text-align: center;">
            <form id="ukForm" onsubmit="event.preventDefault(); submitForm(\'UK\');">
                <input type="text" name="search" placeholder="UK Name Check">
                <button type="submit">Search</button>
            </form>
          </div>';
    echo '<div id="responseContainer" style="margin: auto; margin-bottom: 20px; text-align: center;"></div>';

    echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
    echo '<script>
    function loadForm(country) {
        $("#buttonContainer button").removeClass("active");
        $("#buttonContainer button:contains(" + country + ")").addClass("active");

        $("#formContainer").empty();
        $("#responseContainer").empty();
        $.post("namecheck.php", { load_form: country }, function(response) {
            $("#formContainer").html(response);
        });
    }

    function submitForm(country) {
        var formData = country === "UK" ? $("#ukForm").serialize() : $("#irelandForm").serialize();
        formData += "&country=" + country;

        $.post("/Westmoore-Landing-Page/zebu-multipurpose-landing-page-html-template-2024-07-26-00-35-13-utc/zebu-multipurpose-landing-page-html-template/zebu/namecheck.php", formData, function(response) {
            $("#responseContainer").html(response);
        });
    }
    </script>';
}


// UK function to handle company name search with reserved keyword check
function handleCompanyNameSearch() {
  $apiKey = 'aa6767a3-65f3-4c6c-8f65-f354ae80c340'; // Replace with your actual API key
  $searchQuery = $_POST['search'] ?? '';
  $responseText = '';

  // Define the removeSuffixes function
  function removeSuffixes($name) {
    $ignoreSuffixes = array(
        " LTD", " LIMITED", " PLC", " LLP", " GROUP", " HOLDINGS", " CORPORATION", 
        " CORP", " INC", " LLC", " PARTNERSHIP", " AND CO", " & CO", " AND COMPANY", 
        " & COMPANY", " TRUST", " ASSOCIATES", " ASSOCIATION", " FOUNDATION", " FUND", 
        " INSTITUTE", " SOCIETY", " UNION", " SYNDICATE", " CHAMBERS", " ENTERPRISES", 
        " DEVELOPMENTS", " PROPERTIES", " INVESTMENTS", " TRADING", " SERVICES", 
        " SOLUTIONS", " CONSULTANTS", " CONSULTANCY", " RESOURCES", " PROJECTS", 
        " VENTURES", " COMMERCIAL", " FINANCE", " TECHNOLOGIES", " GLOBAL", 
        " UK", " INTERNATIONAL", " EUROPE", " PARTNERS", " UNLIMITED", " MANAGEMENT", 
        " CAPITAL", " INDUSTRIES", " CO-OPERATIVE", ".COM", " COOPERATIVE", " WHOLESALE", 
        " WHOLESALE SUPPLIES", " UK LIMITED", " GB", " GB LTD", " SCOTLAND LTD", 
        " NORTHERN IRELAND LTD", " WALES LTD", " WALES LIMITED", " WALES PLC", 
        " SCOTLAND PLC", " NORTHERN IRELAND PLC", " ENGLAND LTD", " ENGLAND LIMITED", 
        " ENGLAND PLC", " SCOTLAND LIMITED", " NORTHERN IRELAND LIMITED"
    );
    return preg_replace('/\b(' . implode('|', $ignoreSuffixes) . ')\b/i', '', $name);
  }

  if (!empty($searchQuery)) {
      // Check if the original search query is a reserved keyword or contains a reserved phrase
      $reservedResponse = isReservedKeyword($searchQuery);
      $reservedPhraseResponse = containsReservedPhrase($searchQuery);

      if ($reservedResponse || $reservedPhraseResponse) {
          $responseText = '<div class="response-box" style="background-color: #E67000; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                              <img src="images/checklist.png" alt="Reserved" style="width: 50px; height: 50px; margin-bottom:5px;">
                              <h2>' . htmlspecialchars($searchQuery) . '</h2>
                              <p>' . htmlspecialchars($reservedResponse ?: $reservedPhraseResponse) . '</p>
                           </div>';
      } else {
          // Initial check with the exact name
          $apiUrl = "https://api.companieshouse.gov.uk/search/companies?q=" . urlencode($searchQuery);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $apiUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($apiKey . ':')));
          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          curl_close($ch);

          if ($httpCode == 200) {
              $responseData = json_decode($response, true);
              $companyExists = false;
              foreach ($responseData['items'] as $item) {
                  // Perform an exact match check with and without suffixes
                  $itemTitleWithoutSuffix = removeSuffixes($item['title']);
                  if (strcasecmp($item['title'], $searchQuery) === 0 || strcasecmp($itemTitleWithoutSuffix, $searchQuery) === 0) {
                      $companyExists = true;
                      break;
                  }

                  // Additional check for plural/singular variations
                  $cleanQuery = removeSuffixes($searchQuery);
                  if ((strcasecmp($itemTitleWithoutSuffix, $cleanQuery) === 0) || 
                      (strcasecmp($itemTitleWithoutSuffix, $cleanQuery . 's') === 0) || 
                      (strcasecmp($itemTitleWithoutSuffix, rtrim($cleanQuery, 's')) === 0)) {
                      $companyExists = true;
                      break;
                  }
              }

              // Respond based on company name availability
              if ($companyExists) {
                  $responseText = '<div class="response-box" style="background-color: #ff4f4f; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                                      <img src="images/remove.png" alt="Error" style="width: 50px; height: 50px; margin-bottom:5px;">
                                      <h2>' . htmlspecialchars($searchQuery) . '</h2>
                                      <p>This name is not available for registration.</p>
                                   </div>';
              } else {
                  $responseText = '<div class="response-box" style="background-color: #28a745; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                                      <img src="images/success-icon.png" alt="Success" style="width: 50px; height: 50px; margin-bottom:5px;">
                                      <h2>' . htmlspecialchars($searchQuery) . '</h2>
                                      <p>This name is available for registration!</p>
                                   </div>';
                  $responseText .= '<div style="text-align: center; margin-top: 20px;">
                                      <button id="choosePackageBtn" class="choose-package-btn" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Choose a Package</button>
                                    </div>';
              }
          } else {
              $responseText = '<div class="response-box" style="background-color: #ff4f4f; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                                  <p>Error: Unable to retrieve results. HTTP Code ' . $httpCode . '</p>
                               </div>';
          }
      }
  }
  echo $responseText;
}



// Ireland function to handle company search with similar styling to UK
function ireland() {
  $company_name = $_POST["company_name"] ?? '';
  $responseText = '';

  // Define the removeSuffixes function
  function removeSuffixes($name) {
      $ignoreSuffixes = array(
          " LTD", " LIMITED", " PLC", " LLP", " GROUP", " HOLDINGS", " CORPORATION", 
          " CORP", " INC", " LLC", " PARTNERSHIP", " AND CO", " & CO", " AND COMPANY", 
          " & COMPANY", " TRUST", " ASSOCIATES", " ASSOCIATION", " FOUNDATION", " FUND", 
          " INSTITUTE", " SOCIETY", " UNION", " SYNDICATE", " CHAMBERS", " ENTERPRISES", 
          " DEVELOPMENTS", " PROPERTIES", " INVESTMENTS", " TRADING", " SERVICES", 
          " SOLUTIONS", " CONSULTANTS", " CONSULTANCY", " RESOURCES", " PROJECTS", 
          " VENTURES", " COMMERCIAL", " FINANCE", " TECHNOLOGIES", " GLOBAL", 
          " UK", " INTERNATIONAL", " EUROPE", " PARTNERS", " UNLIMITED", " MANAGEMENT", 
          " CAPITAL", " INDUSTRIES", " CO-OPERATIVE", ".COM", " COOPERATIVE", " WHOLESALE", 
          " WHOLESALE SUPPLIES", " UK LIMITED", " GB", " GB LTD", " SCOTLAND LTD", 
          " NORTHERN IRELAND LTD", " WALES LTD", " WALES LIMITED", " WALES PLC", 
          " SCOTLAND PLC", " NORTHERN IRELAND PLC", " ENGLAND LTD", " ENGLAND LIMITED", 
          " ENGLAND PLC", " SCOTLAND LIMITED", " NORTHERN IRELAND LIMITED"
      );
      return preg_replace('/\b(' . implode('|', $ignoreSuffixes) . ')\b/i', '', $name);
  }

  if (!empty($company_name)) {
      $encoded = http_build_query(array("company_name" => $company_name, "htmlEnc" => "1"));
      $url = "https://services.cro.ie/cws/companies?" . $encoded;
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Basic " . base64_encode("stein.johnsen@westmoore-services.com:e04ac921-36e2-4198-8263-fdb2217372da"),
          "Content-Type: application/json",
          "charset: utf-8"
      ));
      
      $response = curl_exec($ch);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($httpcode == 401) {
          echo '<div class="response-box" style="background-color: #ff4f4f; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                  <p>Error: Authorization credentials are not valid.</p>
               </div>';
          return;
      }

      $results_array = json_decode($response, true);
      if (json_last_error() !== JSON_ERROR_NONE) {
          echo '<div class="response-box" style="background-color: #ff4f4f; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                  <p>Error: Invalid JSON response.</p>
               </div>';
          return;
      }

      $companyExists = false;
      $cleanQuery = removeSuffixes($company_name);

      if (is_array($results_array) && count($results_array) > 0) {
          foreach ($results_array as $item) {
              $itemTitleWithoutSuffix = removeSuffixes($item['company_name']);

              if (strcasecmp($item['company_name'], $company_name) === 0 || 
                  strcasecmp($itemTitleWithoutSuffix, $company_name) === 0 || 
                  strcasecmp($itemTitleWithoutSuffix, $cleanQuery) === 0 ||
                  strcasecmp($itemTitleWithoutSuffix, $cleanQuery . 's') === 0 ||
                  strcasecmp($itemTitleWithoutSuffix, rtrim($cleanQuery, 's')) === 0) {
                  $companyExists = true;
                  break;
              }
          }
      }

      if ($companyExists) {
          $responseText = '<div class="response-box" style="background-color: #ff4f4f; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                              <img src="images/remove.png" alt="Error" style="width: 50px; height: 50px; margin-bottom:5px;">
                              <h2>' . htmlspecialchars($company_name) . '</h2>
                              <p>This name is not available for registration.</p>
                           </div>';
      } else {
          $responseText = '<div class="response-box" style="background-color: #28a745; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                              <img src="images/success-icon.png" alt="Success" style="width: 50px; height: 50px; margin-bottom:5px;">
                              <h2>' . htmlspecialchars($company_name) . '</h2>
                              <p>This name is available for registration!</p>
                           </div>';
          $responseText .= '<div style="text-align: center; margin-top: 20px;">
                              <button id="choosePackageBtn" class="choose-package-btn" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Choose a Package</button>
                            </div>';
      }
  } else {
      $responseText = '<div class="response-box" style="background-color: #ffc107; color: white; padding: 20px; margin-top: 20px; border-radius: 5px; text-align: center;">
                          <p>Please enter a company name to check availability.</p>
                       </div>';
  }

  echo $responseText;
}


// Handle AJAX requests for form load and form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['load_form'])) {
      $country = $_POST['load_form'];
      if ($country === 'UK') {
          echo '<form id="ukForm" onsubmit="event.preventDefault(); submitForm(\'UK\');" style="display: flex; align-items: center; border-radius: 50px; background-color: white; padding: 0; width: 80%; margin: auto;">
                  <input type="text" name="search" placeholder="UK NameCheck" style="flex: 1; border: none; padding: 15px 20px; border-radius: 50px 0 0 50px; outline: none;">
                  <button type="submit" style="padding: 15px 20px; background-color: #E67000; color: white; border: none; border-radius: 0 50px 50px 0; cursor: pointer;">Search</button>
                </form>';
      } elseif ($country === 'Ireland') {
          echo '<form id="irelandForm" onsubmit="event.preventDefault(); submitForm(\'Ireland\');" style="display: flex; align-items: center; border-radius: 50px; background-color: white; padding: 0; width: 80%; margin: auto;">
                  <input type="text" name="company_name" placeholder="Ireland NameCheck" style="flex: 1; border: none; padding: 15px 20px; border-radius: 50px 0 0 50px; outline: none;">
                  <button type="submit" style="padding: 15px 20px; background-color: #E67000; color: white; border: none; border-radius: 0 50px 50px 0; cursor: pointer;">Search</button>
                </form>';
      }
      exit;
  } elseif (isset($_POST['country'])) {
      if ($_POST['country'] === 'UK') {
          handleCompanyNameSearch();
      } elseif ($_POST['country'] === 'Ireland') {
          ireland();
      }
      exit;
  }
}
?>
<script>
function submitForm(country) {
  var formData = country === "UK" ? $("#ukForm").serialize() : $("#irelandForm").serialize();
  var searchQuery = $("input[name='search']").val() || $("input[name='company_name']").val(); // Get the search query
  var nameParam = country === "UK" ? 'ukname' : 'irelandname'; // Set parameter name based on country
  var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + nameParam + '=' + encodeURIComponent(searchQuery);
  
  // Update the URL with the country-specific name parameter without reloading the page
  window.history.pushState({path: newUrl}, '', newUrl);
  
  formData += "&country=" + country;

  $.post("/Westmoore-Landing-Page/namecheck.php", formData, function(response) {
      $("#responseContainer").html(response);
  });
}

// Event listener for the Continue To Form button click
$(document).on("click", "#choosePackageBtn", function() {
  var currentUrl = window.location.href; // Retain the current URL with the search query
  $("#responseContainer").load("new_table.html", function() {
      // Append the back button with Bootstrap classes and Line Awesome icon
      $("#responseContainer").append('<button id="backBtn" class="btn btn-secondary mt-3" onclick="loadOriginalContent()"><i class="la la-arrow-left"></i> Back</button>');
      $("#formContainer").hide();
      $("#buttonContainer").hide();
      
      // Update the browser history to include the query parameter
      window.history.pushState({path: currentUrl}, '', currentUrl);
  });
});

// Function to revert back to the original name check form and response section
function loadOriginalContent() {
  $("#responseContainer").empty(); // Clear the table content
  $("#formContainer").show(); // Show the original form container
  $("#buttonContainer").show(); // Show the button container
  
  // Remove the country-specific name parameter from the URL
  var url = new URL(window.location.href);
  url.searchParams.delete('ukname');
  url.searchParams.delete('irelandname');
  window.history.pushState({path: url.href}, '', url.href);
}

$(document).ready(function() {
  var urlParams = new URLSearchParams(window.location.search);
  var ukname = urlParams.get('ukname');
  var irelandname = urlParams.get('irelandname');
  
  // Prefill the input field based on the URL parameter
  if (ukname) {
      $("input[name='search']").val(ukname); // Set for UK
  } else if (irelandname) {
      $("input[name='company_name']").val(irelandname); // Set for Ireland
  }
});
</script>

