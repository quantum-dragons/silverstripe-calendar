<div id="$Name" class="Calendar widget">
  <h3 class="top">Event Calendar</h3>
  <div class="body">
    <div>
      <p class="monthYear">$LongMonth $Year</p>
      <span class="prev"><a class="eventAction" href="$PrevLink">Prev</a></span>
      <span class="next"><a class="eventAction" href="$NextLink">Next</a></span>
    </div>

    <table>
      <tr>
        <th>M</th>
        <th>T</th>
        <th>W</th>
        <th>T</th>
        <th>F</th>
        <th>S</th>
        <th>S</th>
      </tr>
      <% loop Rows %>
        <tr>
          <% loop Days %>
            <td<% if $Selected %> class="highlight"<% end_if %>>
              <% if $DayExists %>
                <% if $DayLink %>
                  <a class="eventAction" href="$DayLink">$Number</a>
                <% else %>
                  $Number
                <% end_if %>
              <% end_if %>
            </td>
          <% end_loop %>
        </tr>
      <% end_loop %>
    </table>
    <% if $Events %>
      <ul class="events">
        <% loop $Events %>
          <li><a href="$Link">$Title</a></li>
        <% end_loop %>
      </ul>
    <% end_if %>
  </div>
  <div class="url" style="display: none;">$Url</div>
</div>