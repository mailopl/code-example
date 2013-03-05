using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Drawing;
using System.Windows.Forms;
//---------------------------
// List view with fixed double click event.
// Do not run the check() event.
//---------------------------

namespace Toodie
{
    public class FixedListView : ListView
    {
        private const int WM_LBUTTONDBLCLK = 0x0203;

        public FixedListView()
            : base()
        {
        	this.DoubleBuffered = true;
        	this.SetStyle(
  				ControlStyles.AllPaintingInWmPaint |
				ControlStyles.OptimizedDoubleBuffer |
  				ControlStyles.DoubleBuffer,true
  			);
        	this.UpdateStyles();
           	
        }

        protected override void WndProc(ref Message m)
        {
            if (m.Msg == WM_LBUTTONDBLCLK)
            {
                Point p = PointToClient(new Point(Cursor.Position.X, Cursor.Position.Y));
                ListViewItem lvi = GetItemAt(p.X, p.Y);
                if (lvi != null)
                    lvi.Selected = true;
                OnDoubleClick(new EventArgs());
            }
            else
                base.WndProc(ref m);
        }

    }
}
