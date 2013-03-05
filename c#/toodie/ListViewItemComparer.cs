using System;
using System.Windows.Forms;
using System.Drawing;
using System.Collections;
namespace Toodie
{
	class ListViewItemComparer : IComparer {
      private int col;
      public ListViewItemComparer() {
          col=0;
      }
      public ListViewItemComparer(int column) {
          col=column;
      }
      public int Compare(object x, object y) {
          return String.Compare(((ListViewItem)x).SubItems[col].Text, ((ListViewItem)y).SubItems[col].Text);
      }
   }
}
