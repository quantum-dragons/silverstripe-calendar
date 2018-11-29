<div id="$Name" class="Calendar">
  <h3>$LongMonth</h3>
  <table>
    <tr>
      <th>Mon</th>
      <th>Tue</th>
      <th>Wed</th>
      <th>Thu</th>
      <th>Fri</th>
      <th>Sat</th>
      <th>Sun</th>
    </tr>
    <% loop $Rows %>
    <tr>
      <% loop $Days %>
        <td>
          <% if $DayExists %>
            <% if $DayLink %>
              <span><a href="$DayLink">$Number</a></span>
            <% else %>
              <span>$Number</span>
            <% end_if %>
            <% if $Events %>
              <ul>
                <% loop $Events %>
                  <li><a href="$Link">$Title</a></li>
                <% end_loop %>
              </ul>
            <% end_if %>
          <% end_if %>
        </td>
      <% end_loop %>
    </tr>
    <% end_loop %>
  </table>
</div>
